<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\FuelConsumed;

class Fuel
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
