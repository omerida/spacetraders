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
        public int $tradeVolume {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('tradeVolume cannot be negative');
                }
                $this->tradeVolume = $value;
            }
        },
        public SupplyLevel $supply,
        /** @var non-negative-int */
        public int $purchasePrice {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('purchasePrice cannot be negative');
                }
                $this->purchasePrice = $value;
            }
        },
        /** @var non-negative-int */
        public int $sellPrice {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('sellPrice cannot be negative');
                }
                $this->sellPrice = $value;
            }
        },
        public ?TradeActivityLevel $activity = null,
    ) {
    }
}
