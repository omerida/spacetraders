<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Trait\IsWaypointType;
use Phparch\SpaceTraders\Value\SystemSymbol;
use Phparch\SpaceTraders\Value\Waypoint\Symbol;
use Phparch\SpaceTraders\Value\Waypoint\Type;

class Origin
{
    use IsWaypointType;

    public function __construct(
        public readonly Symbol $symbol,
        public readonly Type $type,
        public readonly SystemSymbol $systemSymbol,
        public int $x,
        public int $y,
    ) {
    }
}
