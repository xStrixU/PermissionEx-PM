<?php

declare(strict_types=1);

namespace permissionex\listeners;

use pocketmine\event\Listener;
use permissionex\events\PlayerUpdateGroupEvent;
use permissionex\Main;
use permissionex\managers\FormatManager;

class UpdateGroupListener implements Listener {
	
	public function updateNametag(PlayerUpdateGroupEvent $e) {
		$player = $e->getPlayer();
		
		$group = Main::getInstance()->getGroupManager()->getPlayer($player->getName())->getGroup();
		
		if($group->getNametag() != null)
		 $player->setDisplayName(FormatManager::getFormat($player, $group->getNametag()));
	}
}