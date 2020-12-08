<?php


namespace MadeAja\joinLocation;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Utils;

class LocationTask extends AsyncTask
{
    /** @var array */
    private $playerAddress;

    /** @var array */
    private $playerName;

    public function __construct($ip, $name)
    {
        $this->playerAddress = $ip;
        $this->playerName = $name;
    }

    public function onRun()
    {
        $data = Utils::getURL("http://ip-api.com/json/" . $this->playerAddress);
        $data = json_decode($data, true);
        if (isset($data["message"]) && $data["message"] === "private range") {
            $data["country"] = "server";
        }
        if (isset($data["country"])) {
            $list[$this->playerName] = ["region" => $data["country"] ?? "Unknown", "city" => $data['city']];
        }
        $this->setResult($list);
    }

    public function onCompletion(Server $server)
    {
        $plugin = $server->getPluginManager()->getPlugin("NichiJoinLocation");
        $plugin->displayBroadcast($this->getResult()[$this->playerName]['region'], $this->getResult()[$this->playerName]['city'], $this->playerName);
  }

}