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

use pocketmine\utils\Config;

final class Provider {

    private static ?Config $config = null;
    private static ?Config $data = null;

    /**
     * @return void
     * 
     * load config.yml, data.yml, messages.yml
     */
    public static function init() : void {
        self::getConfig();
        self::getData();
        self::getMessages();
    }

    /**
     * @return Config
     * 
     * get config
     */
    public static function getConfig() : Config {
        if (self::$config === null) {
            self::$config = new Config(Main::getInstance()->getDataFolder() . "config.yml", Config::YAML, [
                "settings" => [
                    "item" => [
                        "name" => "§r§l§bOre Spawner",
                        "id" => 1,
                        "meta" => 0
                    ],
                ],
                "default" => [
                    "delay" => 5,
                    "ores" => [
                        "coal" => [
                            "id" => 16,
                            "meta" => 0,
                            "chance" => 0.5,
                            "default" => [
                                "id" => 1,
                                "meta" => 0
                            ]
                        ],
                        "iron" => [
                            "id" => 15,
                            "meta" => 0,
                            "chance" => 0.3,
                            "default" => [
                                "id" => 1,
                                "meta" => 0
                            ]
                        ],
                        "gold" => [
                            "id" => 14,
                            "meta" => 0,
                            "chance" => 0.2,
                            "default" => [
                                "id" => 1,
                                "meta" => 0
                            ]
                        ],
                        "diamond" => [
                            "id" => 56,
                            "meta" => 0,
                            "chance" => 0.1,
                            "default" => [
                                "id" => 1,
                                "meta" => 0
                            ]
                        ]
                    ]
                ],
                "ore-1" => [
                    "delay" => 3,
                    "ores" => [
                        "coal" => [
                            "id" => 16,
                            "meta" => 0,
                            "chance" => 0.5,
                            "default" => [
                                "id" => 1,
                                "meta" => 0
                            ]
                        ],
                        "iron" => [
                            "id" => 15,
                            "meta" => 0,
                            "chance" => 0.3,
                            "default" => [
                                "id" => 1,
                                "meta" => 0
                            ]
                        ],
                        "gold" => [
                            "id" => 14,
                            "meta" => 0,
                            "chance" => 0.2,
                            "default" => [
                                "id" => 1,
                                "meta" => 0
                            ]
                        ],
                        "diamond" => [
                            "id" => 56,
                            "meta" => 0,
                            "chance" => 0.1,
                            "default" => [
                                "id" => 1,
                                "meta" => 0
                            ]
                        ]
                    ]
                ],
            ]);
        }
        return self::$config;
    }

    /**
     * @return Config
     * 
     * get data
     */
    public static function getData() : Config {
        if (self::$data === null) {
            self::$data = new Config(Main::getInstance()->getDataFolder() . "data.yml", Config::YAML);
        }
        return self::$data;
    }

    /**
     * @return Config
     * 
     * get messages
     */
    public static function getMessages() : Config {
        return new Config(Main::getInstance()->getDataFolder() . "messages.yml", Config::YAML, [
            "cancel" => "§cYou can't place block here!",
            "place" => "§aYou have successfully placed ore spawner!",
            "remove" => "§aYou have successfully removed ore spawner!",
            "mode" => "§aYou have successfully entered mode remove ore spawner!",
            "give" => "§aYou have successfully given ore spawner!",
            "invalid" => "§cInvalid command! Use /orespawner help for more information!",
            "help" => [
                "§aOreSpawner help:",
                "§a/orespawner help - show help",
                "§a/orespawner remove - remove ore spawner",
                "§a/orespawner give <name> <count> - give ore spawner",
            ],
        ]);
    }
}