<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Goods\Detail;
use Phparch\SpaceTraders\Value\Market\TradeGoods;
use Phparch\SpaceTraders\Value\Market\Transaction;
use Phparch\SpaceTraders\Value\Waypoint\Symbol;

class Market
{
    use MapFromArray;

    public function __construct(
        public Symbol $symbol,
        /** @var Detail[] */
        public array $exports,
        /** @var Detail[] */
        public array $imports,
        /** @var Detail[] */
        public array $exchange,
        /** @var Transaction[] */
        public array $transactions = [],
        /** @var TradeGoods[] */
        public array $tradeGoods = [],
    ) {
    }
}
