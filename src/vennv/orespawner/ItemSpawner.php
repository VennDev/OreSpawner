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

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;

final class ItemSpawner {

    /**
     * @param string $level
     * @param int $count
     * 
     * @return Item
     * 
     * get item spawner
     */
    public static function getItemSpawner(string $level, int $count) : Item {
        $item = (new ItemFactory())->get(
            Main::getInstance()->getConfig()->getNested("settings.item.id"),
            Main::getInstance()->getConfig()->getNested("settings.item.meta"),
            $count
        );
        $item->setCustomName(Main::getInstance()->getConfig()->getNested("settings.item.name"));
        $item->setLore([
            "§r§7Level: §r§b" . $level
        ]);
        $item->getNamedTag()->setString("OreSpawner", $level);
        return $item;
    }

    /**
     * @param Player $player
     * 
     * @return string
     *
     * get level of spawner
     */
    public static function getLevelSpawner(Player $player) : string|null {
        if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag("OreSpawner") !== null) {
            return $player->getInventory()->getItemInHand()->getNamedTag()->getString("OreSpawner");
        }
        return null;
    }
}