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

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

class EventListener implements Listener {

    public function __construct(
        private Main $plugin
    ) {}

    /**
     * @param BlockBreakEvent $event
     * 
     * @return void
     * 
     * remove ore spawner when player break it
     */
    public function onBreak(BlockBreakEvent $event) : void {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $position = $block->getPosition();
        $data = Provider::getData();
        if (
            $data->exists(PositionUtil::toString($position)) &&
            Main::getInstance()->isInModeRemoveOreSpawner($player->getName())
        ) {
            $ev = new events\RemoveOreSpawnerEvent($player, $position);
            $ev->call();
            if ($ev->isCancelled()) return;
            $player->getInventory()->addItem(ItemSpawner::getItemSpawner($data->get(PositionUtil::toString($position)), 1));
            Main::getInstance()->setInModeRemoveOreSpawner($player->getName(), false);
            $data->remove(PositionUtil::toString($position));
            $data->save();
            $player->sendMessage(Provider::getMessages()->get("remove"));
            $event->cancel();
            $player->getWorld()->setBlock($position, BlockFactory::getInstance()->get(BlockLegacyIds::AIR, 0));
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * 
     * @return void
     * 
     * add ore spawner when player place it
     */
    public function onPlace(BlockPlaceEvent $event) : void {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $position = $block->getPosition();
        $key = PositionUtil::toString($position);
        $data = Provider::getData()->getAll();
        if (isset($data[$key])) {
            $player->sendMessage(Provider::getMessages()->get("cancel"));
            $event->cancel();
        }
        if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag("OreSpawner") !== null) {
            $ev = new events\CreateOreSpawnerEvent($player, $position);
            $ev->call();
            if ($ev->isCancelled()) return;
            Provider::getData()->set($key, $player->getInventory()->getItemInHand()->getNamedTag()->getString("OreSpawner"));
            Provider::getData()->save();
            $player->sendMessage(Provider::getMessages()->get("place"));
        }
    }
}