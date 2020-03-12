<?php

declare(strict_types=1);

namespace permissionex\listeners;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityLevelChangeEvent;
use permissionex\Main;

class LevelChangeListener implements Listener {
	
	public function updatePermissions(EntityLevelChangeEvent $e) {
		$entity = $e->getEntity();
		
		if($entity instanceof Player) {
			$entity->setLevel($e->getTarget());
		 Main::getInstance()->getGroupManager()->getPlayer($entity->getName())->updatePermissions();
		}
	}
}