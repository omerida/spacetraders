<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\Ship\Reactor\Requirements;

class Reactor
{
    public function __construct(
        public readonly Reactor\Symbol $symbol,
        public readonly string $name,
        public readonly string $description,
        public readonly float $condition, // between 0 and 1
        public readonly float $integrity, // 0-1
        /** @var non-negative-int */
        public int $powerOutput {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('powerOutput cannot be negative');
                }
                $this->powerOutput = $value;
            }
        },
        public readonly Requirements $requirements,
        public readonly int $quality,
    )
    {
    }
}
