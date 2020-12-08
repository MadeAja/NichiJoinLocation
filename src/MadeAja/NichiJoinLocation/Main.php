<?php

namespace MadeAja\NichiJoinLocation;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
    /** @var array */
    public $config;

    /** @var self */
    private static $instance;

    /** onEnable */
    public function onEnable()
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
        $this->getServer()->getAsyncPool()->submitTask(new LocationTask($player->getAddress(), strtolower($player->getName())));
        $event->setJoinMessage("");
    }

    /** displayBroadcast */
    public function displayBroadcast($city, $name)
    {
        $message = str_replace(["{player}", "{city}", "&"], [$name, $city, "§"], $this->config["prefix"]);
        $this->getServer()->broadcastMessage($message);
    }
}