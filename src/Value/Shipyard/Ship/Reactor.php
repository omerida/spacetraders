<?php

namespace Phparch\SpaceTraders\Value\Shipyard\Ship;

use Phparch\SpaceTraders\Value\ShipReactorRequirements;

class Reactor
{
    public function __construct(
        public readonly string $symbol,
        public readonly string $name,
        public readonly string $description,
        public readonly int $powerOutput,
        public readonly float $quality,
        public readonly float $condition,
        public readonly float $integrity,
        public ShipReactorRequirements $requirements,
    ) {
    }
}