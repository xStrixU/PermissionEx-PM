<?php

declare(strict_types=1);

namespace permissionex\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use permissionex\Main;
use permissionex\chat\ChatManager;

class ChatListener implements Listener {
	
	public function chatFormat(PlayerChatEvent $e) {
		$player = $e->getPlayer();
	 $groupManager = Main::getInstance()->getGroupManager();
	 
	 if($groupManager->getPlayer($player->getName())->getGroup()->getFormat() != null) {
	 	$format = ChatManager::getFormat($player, $e->getMessage());
	 	
	 	if(!ChatManager::isChatPerWorld())
	   $e->setFormat($format);
	  else {
	  	$e->setCancelled(true);
	  	
	  	foreach($player->getLevel()->getPlayers() as $p)
	  	 $p->sendMessage($format);
	  }
	 }
	}
}