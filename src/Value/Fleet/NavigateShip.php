<?php

namespace Phparch\SpaceTraders\Value\Fleet;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Ship\Fuel;
use Phparch\SpaceTraders\Value\Ship\Nav;

class NavigateShip
{
    use MapFromArray;

    public function __construct(
        public Fuel $fuel,
        public Nav $nav,
        /** @var array<string, string> */
        public array $events,
    ) {
    }
}
