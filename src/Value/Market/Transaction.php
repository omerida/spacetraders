<?php

namespace Phparch\SpaceTraders\Value\Market;

use Phparch\SpaceTraders\Value\GoodsSymbol;
use Phparch\SpaceTraders\Value\TransactionType;
use Phparch\SpaceTraders\Value\WaypointSymbol;

class Transaction
{
    public function __construct(
        public WaypointSymbol $waypointSymbol,
        public string $shipSymbol,
        public GoodsSymbol $tradeSymbol,
        public TransactionType $type,
        /** @var non-negative-int */
        public int $units,
        public int $pricePerUnit,
        public int $totalPrice,
        public readonly \DateTimeImmutable $timestamp,
    ) {
    }
}
