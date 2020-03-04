<?php

declare(strict_types=1);

namespace permissionex\group;

use pocketmine\{
	Server, Player, IPlayer, OfflinePlayer
};
use pocketmine\permission\PermissionAttachment;
use permissionex\Main;
use permissionex\provider\Provider;

class PlayerGroupManager {
	
	private $player;
	private $attachment = null;
	
	public function __construct(IPlayer $player, Provider $provider) {
		$this->player = $player;
		$this->provider = $provider;
		$this->init();
	}
	
	private function init() : void {
		if($this->player instanceof Player)
		 $this->attachment = $this->player->addAttachment(Main::getInstance());
	}
	
	public function getAttachment() : PermissionAttachment {
		return $this->attachment;
	}
	
	public function getPlayer() : IPlayer {
		return $this->player;
	}
 
 public function getGroups() : array {
 	return $this->provider->getPlayerGroups($this->player);
	}
	
	public function addGroup(Group $group, ?int $time = null) : void {
		if($this->hasGroup($group))
		 $this->removeGroup($group, false);
		if($time != null) {
			$date = date('d.m.Y H:i:s', strtotime(date("H:i:s")) + $time);
 	$this->provider->addPlayerGroup($this->player, $group, $date);
		} else
			$this->provider->addPlayerGroup($this->player, $group);
		
		$this->updatePermissions();
	}
	
	public function addDefaultGroup() : void {
		$defaultGroup = Main::getInstance()->getGroupManager()->getDefaultGroup();
  $this->addGroup($defaultGroup);
 }
	
	public function setGroup(Group $group, ?int $time = null) : void {
		$this->removeGroups();
  $this->addGroup($group, $time);
 }
 
 public function removeGroup(Group $group, bool $addDefault = true) : void {
 	$this->provider->removePlayerGroup($this->player, $group);
 	
 	if($addDefault && $this->getGroup() == null)
 	 $this->addDefaultGroup();
 	
 	$this->updatePermissions();
 }
 
 public function removeGroups() : void {
 	$this->provider->removePlayerGroups($this->player);
 	$this->updatePermissions();
 }
 
 public function hasGroup(?Group $group = null) : bool {
		return $this->provider->hasPlayerGroup($this->player, $group);
 }
 
 // RETURN FIRST GROUP IN HIERARCHY
 public function getGroup() : ?Group {
 	$groups = [];
 	
 	foreach(Main::getInstance()->getGroupManager()->getAllGroups() as $group)
 		if($this->hasGroup($group)) {
 			$rank = $group->getRank() == null ? 0 : $group->getRank();
 		 $groups[$rank][] = $group;
 		}
 		
 	return $groups[max(array_keys($groups))][0];
 }
 
 public function getGroupExpiry(Group $group) :?int {
 	$date = $this->provider->getPlayerGroupExpiryDate($this->player, $group);
 	
 	if($date == null)
 	 return null;
 	
 	return strtotime($date) - time();
 }
 
 public function getPermissions() : array {
 	$permissions = [];
 	
 	foreach($this->getGroups() as $group) {
   foreach($group->getPermissions() as $permission) {
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
  }
  
  foreach($this->provider->getPlayerPermissions($this->player) as $permission) {
  	if($permission == '*') {
   	foreach(Server::getInstance()->getPluginManager()->getPermissions() as $perm)
   	 if(!in_array($perm->getName(), $permissions))
      $permissions[] = $perm->getName();
   	} elseif(!in_array($permission, $permissions))
    $permissions[] = $permission;
  }
  
  return $permissions;
 }
 
 public function addPermission(string $permission, ?int $time = null) : void {
 	if($this->hasPermission($permission))
 	 $this->removePermission($permission);
 	
 	if($time != null) {
 		$date = date('d.m.Y H:i:s', strtotime(date("H:i:s")) + $time);
 		$this->provider->addPlayerPermission($this->player, $permission, $date);
 	} else
   $this->provider->addPlayerPermission($this->player, $permission);
   
 	$this->updatePermissions();
 }
 
 public function removePermission(string $permission) : void {
 	$this->provider->removePlayerPermission($this->player, $permission);
 	$this->updatePermissions();
 }
 
 public function hasPermission(string $permission) : bool {
 	return $this->provider->hasPlayerPermission($this->player, $permission);
 }
 
 public function delete() : void {
 	$this->provider->deleteUser($this->player);
 	$this->updatePermissions();
 }
 
 public function updatePermissions() : void {
 	$player = $this->player;
 	
 	if($player instanceof OfflinePlayer)
   return;
  
  $permissions = [];
  
  foreach($this->getPermissions() as $permission)
  	$permissions[$permission] = true;
  
  $this->attachment->clearPermissions();
  $this->attachment->setPermissions($permissions);
 }
}