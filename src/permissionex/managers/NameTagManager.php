<?php

declare(strict_types=1);

namespace permissionex\managers;

use pocketmine\Player;
use permissionex\Main;

class NameTagManager {
	
	public static function updateNameTag(Player $player) : void {
		$group = Main::getInstance()->getGroupManager()->getPlayer($player->getName())->getGroup();
		
		if($group == null)
		 return;
		
		if($group->getNameTag() == null)
		 $player->setNameTag($player->getName());
		else
		 $player->setNameTag(FormatManager::getFormat($player, $group->getNametag()));
	}
}