<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Route;
use Phparch\SpaceTraders\Value\SystemSymbol;
use Phparch\SpaceTraders\Value\Waypoint\Symbol;

class Nav
{
    use MapFromArray;

    public function __construct(
        public readonly SystemSymbol $systemSymbol,
        public readonly Symbol $waypointSymbol,
        public readonly Route $route,
        public readonly Status $status,
        public readonly FlightMode $flightMode,
    ) {
    }

    public function isInTransit(): bool
    {
        return $this->status === Status::IN_TRANSIT;
    }

    public function isInOrbit(): bool
    {
        return $this->status === Status::IN_ORBIT;
    }

    public function isDocked(): bool
    {
        return $this->status === Status::DOCKED;
    }
}
