<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;

class ScrapTransaction extends Base
{

    public function __construct(
        public readonly WaypointSymbol $waypoint,
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