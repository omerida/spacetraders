<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Trait\IsWaypointType;

class ShipDestination
{
    use IsWaypointType;

    public function __construct(
        public readonly WaypointSymbol $symbol,
        public readonly WaypointType $type,
        public readonly SystemSymbol $systemSymbol,
        public int $x,
        public int $y,
    ) {
    }
}
