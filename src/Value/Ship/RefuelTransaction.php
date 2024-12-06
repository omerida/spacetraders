<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\GoodsSymbol;
use Phparch\SpaceTraders\Value\TransactionType;
use Phparch\SpaceTraders\Value\WaypointSymbol;

class RefuelTransaction
{
    public function __construct(
        public WaypointSymbol $waypointSymbol,
        public string $shipSymbol,
        public GoodsSymbol $tradeSymbol,
        public TransactionType $type,
        /** @var non-negative-int */
        public int $units,
        /** @var non-negative-int */
        public int $pricePerUnit,
        /** @var non-negative-int */
        public int $totalPrice,
        public readonly \DateTimeImmutable $timestamp,
    )
    {
    }
}
