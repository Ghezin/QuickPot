<?php

declare(strict_types=1);

namespace ghezin\main\listeners;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use ghezin\main\Main;

class EventListener implements Listener{
	
	private $plugin;
	
	public function __construct(Main $plugin){
		$this->plugin=$plugin;
	}
	public function onQuit(PlayerQuitEvent $event){
		$player=$event->getPlayer()->getName();
		if(isset($this->plugin->controls[$player])){
			unset($this->plugin->controls[$player]);
		}
	}
	public function onPacketReceived(DataPacketReceiveEvent $event){
		$player=$event->getPlayer();
		$packet=$event->getPacket();
		$controls=$this->plugin->getPlayerControls($player);
		if($packet instanceof LoginPacket){
			$this->plugin->controls[$packet->username ?? "unavailable"]=$packet->clientData["CurrentInputMode"];

		}
		if($packet::NETWORK_ID===LevelSoundEventPacket::NETWORK_ID and $packet->sound===LevelSoundEventPacket::SOUND_ATTACK_NODAMAGE){
			if($controls=="Touch" and $player->getInventory()->getItemInHand()->getId()===Item::SPLASH_POTION){
				$player->getInventory()->getItemInHand()->onClickAir($player, $player->getDirectionVector());
				if(!$player->isCreative()){
					$player->getInventory()->setItem($player->getInventory()->getHeldItemIndex(), Item::get(0));
				}
				$animation=new AnimatePacket();
				$animation->action=AnimatePacket::ACTION_SWING_ARM;
				$animation->entityRuntimeId=$player->getId();
				$this->plugin->getServer()->broadcastPacket($player->getLevel()->getPlayers(), $animation);
			}
		}
	}
	public function onInteract(PlayerInteractEvent $event){
		$player=$event->getPlayer();
		$action=$event->getAction();
		$controls=$this->plugin->getPlayerControls($player);
		if($controls=="Touch" and $action===PlayerInteractEvent::RIGHT_CLICK_BLOCK){
			if($player->getInventory()->getItemInHand()->getId()===Item::SPLASH_POTION){
				$player->getInventory()->getItemInHand()->onClickAir($player, $player->getDirectionVector());
				if(!$player->isCreative()){
					$player->getInventory()->setItem($player->getInventory()->getHeldItemIndex(), Item::get(0));
				}
			}
		}
	}
}
