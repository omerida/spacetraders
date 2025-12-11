<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\Ship\Frame\Requirements;

class Frame
{
    public function __construct(
        public readonly Frame\Symbol $symbol,
        public readonly string $name,
        public readonly string $description,
        public readonly float $condition, // between 0 and 1
        public readonly float $integrity, // 0-1
        /** @var non-negative-int */
        public readonly int $moduleSlots,
        /** @var non-negative-int */
        public readonly int $mountingPoints,
        /** @var non-negative-int */
        public readonly int $fuelCapacity,
        public readonly Requirements $requirements,
        public readonly int $quality,
    )
    {
    }
}
