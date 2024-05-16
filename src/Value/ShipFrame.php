<?php
namespace Phparch\SpaceTraders\Value;
class ShipFrame
{

    public function __construct(
        public readonly string $symbol, //enum
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
        public readonly ShipFrameRequirements $requirements
    )
    {

    }
}