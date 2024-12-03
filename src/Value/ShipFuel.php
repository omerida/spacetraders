<?php

namespace Phparch\SpaceTraders\Value;

class ShipFuel
{
    public function __construct(
        /** @var non-negative-int */
        public readonly int $current,
        /** @var non-negative-int */
        public readonly int $capacity,
        public readonly FuelConsumed $consumed,
    )
    {
    }
}
