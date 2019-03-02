<?php

declare(strict_types=1);

namespace pmessentials\PMEssentials;

use pmessentials\PMEssentials\event\PlayerGodmodeEvent;
use pmessentials\PMEssentials\event\PlayerVanishEvent;
use pmessentials\PMEssentials\Main;
use pocketmine\Player;


class User{
    protected $api;
    protected $plugin;

    protected $player;
    protected $map;
    public $data = []; //data for any plugin to access

    protected $vanish = false;
    protected $godmode = false;

    public function getUserMap() : UserMap{
        return $this->map;
    }

    public function setUserMap(UserMap $map) : void{
        $this->map = $map;
    }

    public function __construct(Player $player){
        $this->player = $player;
        $this->api = API::getAPI();
        $this->plugin = $this->api->getPlugin();
    }

    public function getPlayer() : Player{
        return $this->player;
    }

    protected function setPlayer(Player $player) : void{
        $this->player = $player;
    }

    public function save() : void{

    }

    public function load() : void{

    }

    public function setVanished(bool $bool = true) : void{
        if($bool){
            $this->vanish = true;
            $this->plugin->getServer()->removeOnlinePlayer($this->getPlayer());
            foreach($this->plugin->getServer()->getLoggedInPlayers() as $target){
                if(!$target->hasPermission(Main::PERMISSION_PREFIX."vanish.see")){
                    $target->hidePlayer($this->getPlayer());
                }
            }
        }else{
            $this->vanish = false;
            $this->plugin->getServer()->addOnlinePlayer($this->getPlayer());
            foreach($this->plugin->getServer()->getLoggedInPlayers() as $target){
                if(!$target->canSee($this->getPlayer())){
                    $target->showPlayer($this->getPlayer());
                }
            }
        }
    }

    public function isVanished() : bool{
        return $this->vanish;
    }

    public function setGodmode(bool $bool = true) : void{
        $this->godmode = $bool;
    }

    public function isGodmode() : bool{
        return $this->godmode;
    }
}