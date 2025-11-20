<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Route;
use Phparch\SpaceTraders\Value\SystemSymbol;
use Phparch\SpaceTraders\Value\WaypointSymbol;

class Nav extends Base
{
    public function __construct(
        public readonly SystemSymbol $systemSymbol,
        public readonly WaypointSymbol $waypointSymbol,
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
