<?php

namespace Phparch\SpaceTraders\Value;

class ShipDestination
{
    public function __construct(
        public readonly WaypointSymbol $symbol,
        public readonly WaypointType $type,
        public readonly SystemSymbol $systemSymbol,
        public int $x,
        public int $y,
    )
    {
    }
}
