<?php

namespace Phparch\SpaceTraders\Value;
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
}
