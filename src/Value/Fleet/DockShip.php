<?php

namespace Phparch\SpaceTraders\Value\Fleet;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Ship\Nav;

class DockShip
{
    use MapFromArray;

    public function __construct(
        public Nav $nav,
    ) {
    }
}
