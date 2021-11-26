<?php


namespace MadeAja\NichiJoinLocation;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Internet;
use function json_decode;

class LocationTask extends AsyncTask
{

    /** @var string */
    private $playerAddress;

    /** @var string */
    private $playerName;

    public function __construct($ip, $name)
    {
        $this->playerAddress = $ip;
        $this->playerName = $name;
    }

    public function onRun()
    {
        $data = Internet::getURL("http://ip-api.com/json/{$this->playerAddress}");
        $data = json_decode($data, true);
        if ($data["message"] === "private range") {
            $data["country"] = "server";
            $data["city"] = "server";
        }
        $list[$this->playerName] = ["region" => $data["country"] ?? "Unknown", "city" => $data['city'] ?? "Unknown"];
        $this->setResult($list);
    }

    public function onCompletion(Server $server)
    {
        Main::getInstance()->displayBroadcast($this->getResult()[$this->playerName]['region'], $this->getResult()[$this->playerName]['city'], $this->playerName);
    }

}
