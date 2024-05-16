<?php

namespace Phparch\SpaceTraders\Value;

class ShipCrew {

    public function __construct(
        /** @var non-negative-int */
        public readonly int $current,
        /** @var non-negative-int */
        public readonly int $capacity,
        /** @var non-negative-int */
        public readonly int $required,
        public readonly string $rotation, // enum
        /** @var non-negative-int */
        public readonly int $morale,
        /** @var non-negative-int */
        public readonly int $wages,

    )
    {
    }
}