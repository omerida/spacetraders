<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\WaypointSymbol;

class RefuelTransaction
{
    public function __construct(
        public WaypointSymbol $waypointSymbol,
        public string $shipSymbol,
        public string $tradeSymbol, // enum
        public string $type, // enum
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
