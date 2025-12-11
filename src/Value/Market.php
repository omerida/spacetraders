<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Goods\Detail;
use Phparch\SpaceTraders\Value\Market\TradeGoods;
use Phparch\SpaceTraders\Value\Market\Transaction;

class Market extends Base
{
    public function __construct(
        public WaypointSymbol $symbol,
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
