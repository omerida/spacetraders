<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Ship\Nav;
use Phparch\SpaceTraders\Value\ShipFuel;

class NavigateShip extends Base
{
    public function __construct(
        public ShipFuel $fuel,
        public Nav $nav,
        /** @var array<string, string> */
        public array $events,
    ) {
    }
}
