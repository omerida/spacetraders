<?php

namespace Phparch\SpaceTraders\Value\Shipyard\Ship;

use Phparch\SpaceTraders\Value\ShipFrameRequirements;

class Frame
{
    public function __construct(
        public readonly string $symbol,
        public readonly string $name,
        public readonly string $description,
        public readonly int $moduleSlots,
        public readonly int $mountingPoints,
        public readonly int $fuelCapacity,
        public readonly float $quality,
        public readonly float $condition,
        public readonly float $integrity,
        public ShipFrameRequirements $requirements,
    ) {
    }
}
