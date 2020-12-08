<?php

namespace MadeAja\NichiJoinLocation;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener
{
    /** @var array */
    public $config;

    /** onEnable */
    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig()->getAll();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("Plugin join location has been enable");

    }

    /** onDisable */
    public function onDisable()
    {
        $this->getLogger()->info("Plugin join location has been disable");
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
        $message = str_replace(["{player}", "{city}"], [$name, $city], $this->config["prefix"]);
        $this->getServer()->broadcastMessage($message);
    }
}