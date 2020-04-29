<?php

declare(strict_types=1);

namespace permissionex\chat;

use pocketmine\Player;
use permissionex\Main;

class ChatManager {
	
	public static function getFormat(Player $player, string $message) : string {
		$group = Main::getInstance()->getGroupManager()->getPlayer($player->getName())->getGroup();
		$format = $group->getFormat();
		
		$format = str_replace("{GROUP}", $group->getName(), $format);
		$format = str_replace("{DISPLAYNAME}", $player->getDisplayName(), $format);
		$format = str_replace("{MESSAGE}", $message, $format);
		
		// YOU CAN ADD YOUR OWN STUFF HEFE
		
		return $format;
	}
	
	public static function setChatPerWorld(bool $status = true) : void {
		$settings = Main::getInstance()->getSettings();
		
		$settings->set("chat-per-world", $status);
		$settings->save();
	}
	
	public static function isChatPerWorld(bool $status = true) : bool {
		$settings = Main::getInstance()->getSettings();
		
		return (bool) $settings->get("chat-per-world");
	}
}