<?php


namespace MadeAja\NichiJoinLocation;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;
use function json_decode;

class LocationTask extends AsyncTask
{

    private string $playerAddress;

    private string $playerName;

    public function __construct($ip, $name)
    {
        $this->playerAddress = $ip;
        $this->playerName = $name;
    }

    public function onRun() : void
    {
        $data = Internet::getURL("http://ip-api.com/json/$this->playerAddress")->getBody();
        $data = json_decode($data, true);
        if ($data["message"] === "private range") {
            $data["country"] = "server";
            $data["city"] = "server";
        }
        $list[$this->playerName] = ["region" => $data["country"] ?? "Unknown", "city" => $data['city'] ?? "Unknown"];
        $this->setResult($list);
    }

    public function onCompletion() : void
    {
        Main::getInstance()->displayBroadcast($this->getResult()[$this->playerName]['region'], $this->getResult()[$this->playerName]['city'], $this->playerName);
    }

}
