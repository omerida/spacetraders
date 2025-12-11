<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\Ship\Engine\Requirements;

class Engine
{
    public function __construct(
        public readonly Engine\Symbol $symbol,
        public readonly string $name,
        public readonly string $description,
        public readonly float $condition, // between 0 and 1
        public readonly float $integrity, // 0-1
        /** @var positive-int */
        public readonly int $speed,
        public readonly Requirements $requirements,
        public readonly int $quality,
    )
    {
    }
}
