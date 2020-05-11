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

	public function onEnable(){

		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		@mkdir($this->getDataFolder());

		$this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, [
			"message" => "§cTeleported to spawn",
			"yaw" => 0,
			"pitch" => 0
		]);

	}

	public function onLogin(PlayerLoginEvent $ev){
		$p = $ev->getPlayer();
		$pos = $this->getServer()->getDefaultLevel()->getSafeSpawn();
		$p->teleport(new Position($pos->x, $pos->y, $pos->z, $pos->getLevel()), $this->config->get("yaw"), $this->config->get("pitch"));
	}
	public function onJoin(PlayerJoinEvent $ev){
		$p = $ev->getPlayer();
		$pos = $this->getServer()->getDefaultLevel()->getSafeSpawn();
		$p->teleport(new Position($pos->x, $pos->y, $pos->z, $pos->getLevel()), $this->config->get("yaw"), $this->config->get("pitch"));
	}

	public function onCommand(CommandSender $p, Command $c, string $l, array $a) : bool {
		if($c->getName() == "setrotate"){
			if($p->isOp()){
				$this->config->set("yaw", $p->getYaw());
				$this->config->set("pitch", $p->getPitch());
				$this->config->save();
				$p->sendMessage("§aSucess");
			}
		}else{
			$pos = $this->getServer()->getDefaultLevel()->getSafeSpawn();
			$p->teleport(new Position($pos->x, $pos->y, $pos->z, $pos->getLevel()), $this->config->get("yaw"), $this->config->get("pitch"));
			$p->sendMessage($this->config->get("message"));
		}
		return true;
	}
}
