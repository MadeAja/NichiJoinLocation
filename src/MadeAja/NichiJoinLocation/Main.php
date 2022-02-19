<?php

namespace MadeAja\NichiJoinLocation;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
    public array $config;

    private static Main $instance;

    /** onEnable */
    protected function onEnable() : void
    {
        self::$instance = $this;
        $this->saveDefaultConfig();
        $this->config = $this->getConfig()->getAll();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public static function getInstance() : self {
        return self::$instance;
    }

    /** Event onJoin */
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $this->getServer()->getAsyncPool()->submitTask(new LocationTask($player->getNetworkSession()->getIp(), strtolower($player->getName())));
        $event->setJoinMessage("");
    }

    /** displayBroadcast */
    public function displayBroadcast($region, $city, $name)
    {
        $message = str_replace(["{player}", "{region}", "{city}", "&"], [$name, $region, $city, "§"], $this->config["prefix"]);
        $this->getServer()->broadcastMessage($message);
    }

}