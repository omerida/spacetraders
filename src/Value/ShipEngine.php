<?php

namespace Phparch\SpaceTraders\Value;

class ShipEngine
{
    public function __construct(
        public readonly Ship\EngineSymbol $symbol,
        public readonly string $name,
        public readonly string $description,
        public readonly float $condition, // between 0 and 1
        public readonly float $integrity, // 0-1
        /** @var positive-int */
        public readonly int $speed,
        public readonly ShipEngineRequirements $requirements
    )
    {
    }
}
