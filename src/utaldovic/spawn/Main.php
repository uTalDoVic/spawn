<?php

namespace utaldovic\spawn;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\{
	CommandSender,
	Command
};
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

	public function onEnable(){

		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		@mkdir($this->getDataFolder());

		$this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, [
			"message" => "§cTeleported to spawn"
		]);

	}

	public function onLogin(PlayerLoginEvent $ev){
		$p = $ev->getPlayer();
		$p->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
	}

	public function onCommand(CommandSender $p, Command $c, string $l, array $a) : bool {
		$p->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
		$p->sendMessage($this->config->get("message"));
		return true;
	}
}
