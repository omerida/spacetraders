<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Ship\Nav;
use Phparch\SpaceTraders\Value\Ship\Fuel;

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
