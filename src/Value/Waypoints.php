<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Trait\MapFromArray;

class Waypoints
{
    use MapFromArray;

    public function __construct(
        /** @var \Phparch\SpaceTradersRest\Value\Waypoint[] */
        public array $waypoints,
    ) {
    }
}
