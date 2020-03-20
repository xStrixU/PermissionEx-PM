<?php

declare(strict_types=1);

namespace permissionex\listeners;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityLevelChangeEvent;
use permissionex\Main;
use permissionex\managers\FormatManager;

class LevelChangeListener implements Listener {
	
	public function updatePermissions(EntityLevelChangeEvent $e) {
		$entity = $e->getEntity();
		
		if($entity instanceof Player) {
			$entity->setLevel($e->getTarget());
		 Main::getInstance()->getGroupManager()->getPlayer($entity->getName())->updatePermissions();
		}
	}
	
	public function updateNametag(EntityLevelChangeEvent $e) {
		$player = $e->getPlayer();
		
		$group = Main::getInstance()->getGroupManager()->getPlayer($player->getName())->getGroup();
		
		if($group->getNametag() != null)
		 $player->setDisplayName(FormatManager::getFormat($player, $group->getNametag()));
	}
}