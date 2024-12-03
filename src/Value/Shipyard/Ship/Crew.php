<?php

namespace Phparch\SpaceTraders\Value\Shipyard\Ship;

class Crew
{
    public function __construct(
        /** @var non-negative-int */
        public readonly int $capacity,
        public readonly int $required,
    ) {
    }
}
