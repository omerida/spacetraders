<?php

namespace Phparch\SpaceTraders\Value\Market;

use Phparch\SpaceTraders\Value\GoodsActivityLevel;
use Phparch\SpaceTraders\Value\GoodsSupplyLevel;
use Phparch\SpaceTraders\Value\GoodsSymbol;
use Phparch\SpaceTraders\Value\TradegoodType;

class TradeGoods
{
    public function __construct(
        public readonly GoodsSymbol $symbol,
        public readonly TradegoodType $type,
        /** @var non-negative-int */
        public int $tradeVolume,
        public GoodsSupplyLevel $supply,
        /** @var non-negative-int */
        public int $purchasePrice,
        /** @var non-negative-int */
        public int $sellPrice,
        public ?GoodsActivityLevel $activity = null,
    ) {
    }
}
