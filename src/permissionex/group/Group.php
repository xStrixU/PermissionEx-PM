<?php

declare(strict_types=1);

namespace permissionex\group;

use pocketmine\Server;
use permissionex\Main;
use permissionex\provider\Provider;

class Group {
	
	private $name;
	private $provider;
	private $data;
	
	public function __construct(string $name, Provider $provider, array $data) {
		$this->name = $name;
		$this->provider = $provider;
		$this->data = $data;
	}
	
	public function getName() : string {
		return $this->name;
	}
	
	public function getProvider() : Provider {
		return $this->provider;
	}
	
	public function getData() : array {
		return $this->data;
	}
	
	public function setData(array $data) : void {
		$cfg = Main::getInstance()->getGroupManager()->getConfig();
		
		$groups = $cfg->get("groups");
		
		$groups[$this->getName()] = $data;
		
		$cfg->set("groups", $groups);
		$cfg->save();
		
		$this->reload();
		$this->updatePlayersPermissions();
	}
	
	public function getRank() : ?int {
		if(!isset($this->data['rank']))
		 return null;
		
		return $this->data['rank'];
	}
	
	public function setRank(int $rank) : void {
		$data = $this->data;
		$data['rank'] = $rank;
		$this->setData($data);
	}
	
	public function getFormat() : ?string {
		if(!isset($this->data['format']))
		 return null;
		
		return $this->data['format'];
	}
	
	public function setFormat(string $format) : void {
		$data = $this->data;
		$data['format'] = $format;
		$this->setData($data);
	}
	
	public function removeFormat() : void {
		$data = $this->data;
		unset($data['format']);
		$this->setData($data);
	}
	
	public function getPermissions() : array {
		$perms = $this->data['permissions'];
		
		foreach($this->data['parents'] as $groupName)
		 $perms = array_merge($perms, Main::getInstance()->getGroupManager()->getGroup($groupName)->getPermissions());
		 
		 $permissions = [];
		 
		 foreach(array_unique($perms) as $permission) {
		 	if($permission == '*') {
		 		if(!in_array($permission, $permissions))
      $permissions[] = $permission;
     
   		foreach(Server::getInstance()->getPluginManager()->getPermissions() as $perm)
   		 if(!in_array($perm->getName(), $permissions))
       $permissions[] = $perm->getName();
       
       // PERMISSION.*
   	} elseif(substr($permission, -1) == '*') {
   		 if(!in_array($permission, $permissions))
   		  $permissions[] = $permission;
   		foreach(Server::getInstance()->getPluginManager()->getPermissions() as $perm)
   		 if(substr($perm->getName(), 0, strlen($permission)-1) == substr($permission, 0, strlen($permission)-1))
   		  $permissions[] = $perm->getName();
   	} elseif(!in_array($permission, $permissions))
     $permissions[] = $permission;
		 }
		 
		 return $permissions;
	}
	
	public function hasPermission(string $permission) : bool {
		return in_array($permission, $this->getPermissions());
	}
	
	public function getPlayers() : array {
		return $this->provider->getGroupPlayers($this);
	}
	
	public function updatePlayersPermissions() : void {
		foreach($this->getPlayers() as $nick)
		 Main::getInstance()->getGroupManager()->getPlayer($nick)->updatePermissions();
		
		foreach($this->getChildrens() as $parent)
		 $parent->updatePlayersPermissions();
	}
	
	public function getParents() : array {
		$parentGroups = [];
		
		foreach($this->data['parents'] as $groupName)
		 $parentGroups[] = Main::getInstance()->getGroupManager()->getGroup($groupName);
		
		return $parentGroups;
	}
	
	public function getChildrens() : array {
		$childrens = [];
		
		foreach(Main::getInstance()->getGroupManager()->getAllGroups() as $group)
		 foreach($group->getParents() as $parent)
		  if($parent->getName() == $this->getName())
		   $childrens[] = $group;
		
		return $childrens;
	}
	
	public function delete() : void {
		$cfg = Main::getInstance()->getGroupManager()->getConfig();
		
 	$groups = $cfg->get("groups");
 	
 	unset($groups[$this->name]);
 	
 	$cfg->set("groups", $groups);
 	$cfg->save();
 	
 	foreach($this->getPlayers() as $nick)
 	 Main::getInstance()->getGroupManager()->getPlayer($nick)->removeGroup($this);
 }
	
	public function addPermission(string $permission) : void {
 	$groupData = $this->data;
 	
 	if(!in_array($permission, $groupData['permissions']))
  	$groupData['permissions'][] = $permission;
  
  $this->setData($groupData);
 }
 
 public function removePermission(string $permission) : void {
 	$groupData = $this->data;
 	
 	$perms = [];
 	
 	unset($groupData['permissions'][array_search($permission, $groupData['permissions'])]);
 	
 	// ARRAY SORT
 	foreach($groupData['permissions'] as $perm)
 	 $perms[] = $perm;
 	
 	$groupData['permissions'] = $perms;
 	
 	$this->setData($groupData);
 }
 
 public function addParent(Group $parent) : void {
 	if($this->hasParent($parent) || $parent->getName() == $this->getName())
 	 return;
 	
 	
 	$groupData = $this->data;
 	
 	if(!in_array($parent, $groupData['parents']))
  	$groupData['parents'][] = $parent->getName();
  
  $this->setData($groupData);
 }
 
 public function removeParent(Group $parent) : void {
 	$groupData = $this->data;
 	
 	$parents = [];
 	
 	unset($groupData['parents'][array_search($parent->getName(), $groupData['parents'])]);
 	
 	// ARRAY SORT
 	foreach($groupData['parents'] as $parent)
 	 $parents[] = $parent;
 	
 	$groupData['parents'] = $parents;
 	
 	$this->setData($groupData);
 }
 
 public function removeParents() : void {
 	foreach($this->getParents() as $parent)
 	 $this->removeParent($parent);
 }
 
 public function hasParent(Group $parent) : bool {
 	foreach($this->getParents() as $group)
 	 if($parent->getName() == $group->getName())
 	  return true;
 	
 	return false;
 }
 
 public function reload() : void {
 	$this->data = Main::getInstance()->getGroupManager()->getGroup($this->getName())->getData();
 }
}