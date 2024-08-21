<?php

namespace Phparch\SpaceTraders\Value;

class Nav
{
    public function __construct(
        public readonly SystemSymbol $systemSymbol,
        public readonly WaypointSymbol $waypointSymbol,
        public readonly Route $route,
        public readonly string $status, // enum
        public readonly string $flightMode, // enum
    )
    {
    }
}