<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Route;
use Phparch\SpaceTraders\Value\ShipFuel;
use Phparch\SpaceTraders\Value\SystemSymbol;
use Phparch\SpaceTraders\Value\WaypointSymbol;
use Phparch\SpaceTradersCLI\Render\Ship\Fuel;

class NavPatch extends Base
{
    public function __construct(
        public readonly Nav $nav,
        public readonly ShipFuel $fuel,
        /** @var array<string, string> */
        public readonly array $events,
    ) {
    }
}
