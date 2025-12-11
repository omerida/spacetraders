<?php

namespace Phparch\SpaceTraders\Value\Ship\Crew;

use Phparch\SpaceTraders\Value\Ship;

class Details
{
    public function __construct(
        /** @var non-negative-int */
        public readonly int $current,
        /** @var non-negative-int */
        public readonly int $capacity,
        /** @var non-negative-int */
        public readonly int $required,
        public readonly Ship\Crew\Rotation $rotation,
        /** @var non-negative-int */
        public readonly int $morale,
        /** @var non-negative-int */
        public readonly int $wages,
    )
    {
    }
}
