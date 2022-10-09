<?php

/**
 *  Copyright (c) 2022 vennv
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 *  SOFTWARE.
 */

namespace vennv\orespawner;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\player\Player;

class Main extends PluginBase implements Listener {

    private static ?Main $instance = null;
    private static array $inModeRemoveOrerSpawner = [];

    /**
     * @return Main
     */
    public static function getInstance() : Main {
        return self::$instance;
    }

    public function onEnable() : void {
        self::$instance = $this;
        Provider::init();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getScheduler()->scheduleRepeatingTask(new OreSpawnerTask($this), 20);
    }

    /**
     * @param string $name
     * 
     * @return bool
     * 
     * check if player is in mode remove ore spawner
     */
    public static function isInModeRemoveOreSpawner(string $name) : bool {
        return isset(self::$inModeRemoveOrerSpawner[$name]);
    }

    /**
     * @param string $name
     * @param bool $value
     * 
     * @return void
     * 
     * use this method to set the player in mode remove ore spawner
     */
    public static function setInModeRemoveOreSpawner(string $name, bool $value) : void {
        if ($value) {
            self::$inModeRemoveOrerSpawner[$name] = true;
        } else {
            unset(self::$inModeRemoveOrerSpawner[$name]);
        }
    }

    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * 
     * @return bool
     * 
     * use this method to handle the command
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        if (in_array($command->getName(), ["orespawner", "osp", "os"])) {
            if (isset($args[0])) {
                if ($args[0] === "remove") {
                    self::setInModeRemoveOreSpawner($sender->getName(), true);
                    $sender->sendMessage(Provider::getMessages()->get("mode"));
                }
                if ($args[0] === "give") {
                    if (!$sender instanceof Player){
                        $sender->sendMessage("§r§cPlease run this command in-game!");
                        return true;
                    }
                    if (!$sender->hasPermission('orespawner.command.give')) return true;
                    if (isset($args[1]) && isset($args[2])) {
                        $sender->getInventory()->addItem(ItemSpawner::getItemSpawner($args[1], (int) $args[2]));
                        $sender->sendMessage(Provider::getMessages()->get("give"));
                    } else {
                        $sender->sendMessage(Provider::getMessages()->get("invalid"));
                    }
                }
            } else {
                foreach(Provider::getMessages()->get("help") as $message) {
                    $sender->sendMessage($message);
                }
            }
        }
        return true;
    }
}