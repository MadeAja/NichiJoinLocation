<?php


namespace MadeAja\NichiJoinLocation;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

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
        $data = file_get_contents("http://ip-api.com/json/{$this->playerAddress}");
        $data = json_decode($data, true);
        if ($data["message"] === "private range") {
            $data["country"] = "server";
        }
        $list[$this->playerName] = ["region" => $data["country"] ?? "Unknown", "city" => $data['city'] ?? "Unknown"];
        $this->setResult($list);
    }

    public function onCompletion(Server $server)
    {
        Main::getInstance()->displayBroadcast($this->getResult()[$this->playerName]['region'], $this->getResult()[$this->playerName]['city'], $this->playerName);
    }

}