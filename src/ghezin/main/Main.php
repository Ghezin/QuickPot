<?php

declare(strict_types=1);

namespace ghezin\main;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use ghezin\main\listeners\EventListener;

class Main extends PluginBase{
	
	private $availableCtrls=["Unknown", "Mouse", "Touch", "Controller"];
	
	public $controls=[];
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
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
