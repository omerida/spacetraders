<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Ship\Nav;
use Phparch\SpaceTraders\Value\ShipFuel;

class DockShip extends Base
{
    public function __construct(
        public Nav $nav,
    ) {
    }
}
