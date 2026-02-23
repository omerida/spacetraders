<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\Ship\Reactor\Requirements;

class Reactor
{
    public function __construct(
        public readonly Reactor\Symbol $symbol,
        public readonly string $name,
        public readonly string $description,
        public float $condition {
            set {
                if ($value < 0 || $value > 1) {
                    throw new \OutOfRangeException('Condition must be between 0 and 1');
                }
                $this->condition = $value;
            }
        },
        public float $integrity {
            set {
                if ($value < 0 || $value > 1) {
                    throw new \OutOfRangeException('Integrity must be between 0 and 1');
                }
                $this->integrity = $value;
            }
        },
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
