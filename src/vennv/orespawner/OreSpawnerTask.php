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
use pocketmine\scheduler\Task;

class OreSpawnerTask extends Task {

    private static array $delay = [];

    public function __construct() {}

    public function onRun() : void {
        $config = Provider::getConfig();
        $data = Provider::getData()->getAll();
        foreach($data as $key => $value) {
            $position = PositionUtil::toPosition($key);
            $block = $position->getWorld()->getBlock($position->asVector3());
            if (
                $block->getId() == 0 &&
                $position->getWorld()->isLoaded() &&
                $position->getWorld()->isChunkLoaded($position->getFloorX() >> 4, $position->getFloorZ() >> 4)
            ) {
                $resultData = $config->get($value);
                if (!$resultData || $resultData == null) return;
                $delay = $resultData["delay"];
                $oresData = $resultData["ores"];
                $choose = $oresData[array_rand($oresData)];
                $chance = $choose["chance"];
                $defaultBlock = $choose["default"];
                if (!isset(self::$delay[$key])) {
                    self::$delay[$key] = $delay;
                }
                if (--self::$delay[$key] <= 0) {
                    if ((mt_rand(1, 100) / 100) <= $chance) {
                        $block = BlockFactory::getInstance()->get($choose["id"], $choose["meta"]);
                        $position->getWorld()->setBlock($position->asVector3(), $block);
                    } else {
                        $block = BlockFactory::getInstance()->get($defaultBlock["id"], $defaultBlock["meta"]);
                        $position->getWorld()->setBlock($position->asVector3(), $block);
                    }
                    self::$delay[$key] = $delay;
                }
            }
        }
    }
}
