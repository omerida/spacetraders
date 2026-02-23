<?php

namespace Phparch\SpaceTraders\Value\Shipyard\Ship;

class Crew
{
    public function __construct(
        /** @var non-negative-int */
        public int $capacity {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('capacity cannot be negative');
                }
                $this->capacity = $value;
            }
        },
        public readonly int $required,
    ) {
    }
}
