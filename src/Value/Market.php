<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\GoodsDetail;
use Phparch\SpaceTraders\Value\Market\Transaction;

class Market extends Base
{
    public function __construct(
        public WaypointSymbol $symbol,
        /** @var GoodsDetail[] */
        public array $exports,
        /** @var GoodsDetail[] */
        public array $imports,
        /** @var GoodsDetail[] */
        public array $exchange,
        /** @var Transaction[] */
        public array $transactions,
        public array $tradeGoods,
    ) {
    }
}
