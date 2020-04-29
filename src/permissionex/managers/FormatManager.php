<?php

declare(strict_types=1);

namespace permissionex\managers;

use pocketmine\Player;
use permissionex\Main;

class FormatManager {
	
	public static function getFormat(Player $player, string $format, ?string $message = null) : string {
        $group = Main::getInstance()->getGroupManager()->getPlayer($player->getName())->getGroup();

        $format = str_replace("&", "§", $format);
        $format = str_replace("{GROUP}", $group->getName(), $format);
        $format = str_replace("{DISPLAYNAME}", $player->getDisplayName(), $format);

        // CHAT MESSAGE
		if($message != null)
		 $format = str_replace("{MESSAGE}", $message, $format);
		 
		 // YOU CAN ADD YOUR OWN STUFF HEFE

		return $format;
	}
}