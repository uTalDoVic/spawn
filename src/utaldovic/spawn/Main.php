<?php

namespace utaldovic\spawn;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\{
	CommandSender,
	Command
};
use pocketmine\level\Position;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
	
	public $cfg;

	public function onEnable(){

		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		@mkdir($this->getDataFolder());

		$this->cfg = new Config($this->getDataFolder()."config.yml", Config::YAML, [
			"message" => "§cTeleported to spawn",
			"yaw" => 0,
			"pitch" => 0,
			"cooldown" => -1
		]);

	}
	
	public function onJoin(PlayerJoinEvent $ev){
		$p = $ev->getPlayer();
		$pos = $this->getServer()->getDefaultLevel()->getSafeSpawn();
		$p->teleport(new Position($pos->x, $pos->y, $pos->z, $pos->getLevel()), $this->cfg->get("yaw"), $this->cfg->get("pitch"));
	}

	public function onCommand(CommandSender $p, Command $c, string $l, array $a) : bool {
		if($c->getName() == "setrotate"){
			if($p->isOp()){
				$this->cfg->set("yaw", $p->getYaw());
				$this->cfg->set("pitch", $p->getPitch());
				$this->cfg->save();
				$p->sendMessage("§aSucess");
			}
		}else{
			$pos = $this->getServer()->getDefaultLevel()->getSafeSpawn();
			$p->teleport(new Position($pos->x, $pos->y, $pos->z, $pos->getLevel()), $this->cfg->get("yaw"), $this->cfg->get("pitch"));
			$p->sendMessage($this->cfg->get("message"));
		}
		return true;
	}
}
