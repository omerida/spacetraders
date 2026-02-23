<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\FuelConsumed;

class Fuel
{
    public function __construct(
        /** @var non-negative-int */
        public int $current {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('current cannot be negative');
                }
                $this->current = $value;
            }
        },
        /** @var non-negative-int */
        public int $capacity {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('capacity cannot be negative');
                }
                $this->capacity = $value;
            }
        },
        public readonly FuelConsumed $consumed,
    )
    {
    }
}
