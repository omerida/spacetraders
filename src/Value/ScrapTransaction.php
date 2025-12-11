<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Waypoint\Symbol;

class ScrapTransaction extends Base
{
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
