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
        public readonly int $totalPrice,
        public readonly \DateTimeImmutable $timestamp,
    )
    {
    }
}
