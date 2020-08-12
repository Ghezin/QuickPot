<?php

declare(strict_types=1);

namespace ghezin\main;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\network\mcpe\protocol\EventPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use ghezin\main\listeners\EventListener;
use ghezin\main\FormUtils;

class Main extends PluginBase{
	
	private static $instance;
	
	private $availableCtrls=["Unknown", "Mouse", "Touch", "Controller"];
	
	public $controls=[];
	
	public function onEnable():void{
		self::$instance=$this;
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
	}
	public static function getInstance():Core{
		return self::$instance;
	}
	public function getPlayerControls($player):?string{
		if($player instanceof Player) $player=$player->getName();
		if(!isset($this->controls[$player])){
			return "NONE";
		}else{
			return $this->availableCtrls[$this->controls[$player]];
		}
	}
}
