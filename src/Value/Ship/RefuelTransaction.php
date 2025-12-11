<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\Goods;
use Phparch\SpaceTraders\Value\TransactionType;
use Phparch\SpaceTraders\Value\Waypoint;

class RefuelTransaction
{
    public function __construct(
        public Waypoint\Symbol $waypointSymbol,
        public string $shipSymbol,
        public Goods\Symbol $tradeSymbol,
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
