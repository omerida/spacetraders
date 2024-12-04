<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Ship\Nav;

class OrbitShip extends Base
{
    public function __construct(
        public Nav $nav,
    ) {
    }
}
