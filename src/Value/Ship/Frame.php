<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\Ship\Frame\Requirements;

class Frame
{
    public function __construct(
        public readonly Frame\Symbol $symbol,
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
        public int $moduleSlots {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('moduleSlots cannot be negative');
                }
                $this->moduleSlots = $value;
            }
        },
        /** @var non-negative-int */
        public int $mountingPoints {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('mountingPoints cannot be negative');
                }
                $this->mountingPoints = $value;
            }
        },
        /** @var non-negative-int */
        public int $fuelCapacity {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('fuelCapacity cannot be negative');
                }
                $this->fuelCapacity = $value;
            }
        },
        public readonly Requirements $requirements,
        public readonly int $quality,
    )
    {
    }
}
