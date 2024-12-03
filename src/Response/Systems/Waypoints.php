<?php

namespace Phparch\SpaceTraders\Response\Systems;

use Phparch\SpaceTraders\Response\Base;

class Waypoints extends Base
{
    public function __construct(
        /** @var \Phparch\SpaceTraders\Response\Systems\Waypoint[] */
        public array $waypoints,
    ) {
    }
}
