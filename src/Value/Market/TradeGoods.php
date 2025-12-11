<?php

namespace Phparch\SpaceTraders\Value\Market;

use Phparch\SpaceTraders\Value\Goods\Symbol;
use Phparch\SpaceTraders\Value\Goods\SupplyLevel;
use Phparch\SpaceTraders\Value\Goods\TradeActivityLevel;
use Phparch\SpaceTraders\Value\TradegoodType;

class TradeGoods
{
    public function __construct(
        public readonly Symbol $symbol,
        public readonly TradegoodType $type,
        /** @var non-negative-int */
        public int $tradeVolume,
        public SupplyLevel $supply,
        /** @var non-negative-int */
        public int $purchasePrice,
        /** @var non-negative-int */
        public int $sellPrice,
        public ?TradeActivityLevel $activity = null,
    ) {
    }
}
