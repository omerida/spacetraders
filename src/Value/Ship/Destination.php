<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Trait\IsWaypointType;
use Phparch\SpaceTraders\Value\System;
use Phparch\SpaceTraders\Value\Waypoint;

class Destination
{
    use IsWaypointType;

    public function __construct(
        public readonly Waypoint\Symbol $symbol,
        public readonly Waypoint\Type $type,
        public readonly System\Symbol $systemSymbol,
        public int $x,
        public int $y,
    ) {
    }
}
