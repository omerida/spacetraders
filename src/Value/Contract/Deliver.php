<?php

namespace Phparch\SpaceTraders\Value\Contract;

class Deliver
{
    public function __construct(
        public readonly string $tradeSymbol,
        public readonly string $destinationSymbol,
        /** @var non-negative-int */
        public int $unitsRequired {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('unitsRequired cannot be negative');
                }
                $this->unitsRequired = $value;
            }
        },
        /** @var non-negative-int */
        public int $unitsFulfilled {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('unitsFulfilled cannot be negative');
                }
                $this->unitsFulfilled = $value;
            }
        },
    ) {
    }
}
