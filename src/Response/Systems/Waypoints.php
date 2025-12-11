<?php

namespace Phparch\SpaceTraders\Response\Systems;

use Phparch\SpaceTraders\Trait\MapFromArray;

class Waypoints
{
    use MapFromArray;

    public function __construct(
        /** @var \Phparch\SpaceTraders\Response\Systems\Waypoint[] */
        public array $waypoints,
    ) {
    }
}
