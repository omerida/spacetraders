<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Waypoint\Symbol;

class ScrapTransaction
{
    use MapFromArray;

    public function __construct(
        public readonly Symbol $waypoint,
        public readonly string $ship,
        /**
         * @var non-negative-int
         */
        public int $totalPrice {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('totalPrice cannot be negative');
                }
                $this->totalPrice = $value;
            }
        },
        public readonly \DateTimeImmutable $timestamp,
    )
    {
    }
}
