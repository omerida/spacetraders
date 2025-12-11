<?php

namespace Phparch\SpaceTraders\Value\Shipyard\Ship;

use Phparch\SpaceTraders\Value\Ship\Engine\Requirements;

class Engine
{
    public function __construct(
        public readonly string $symbol,
        public readonly string $name,
        public readonly string $description,
        public readonly int $speed,
        public readonly float $quality,
        public readonly float $condition,
        public readonly float $integrity,
        public Requirements $requirements,
    ) {
    }
}
