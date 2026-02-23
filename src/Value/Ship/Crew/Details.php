<?php

namespace Phparch\SpaceTraders\Value\Ship\Crew;

use Phparch\SpaceTraders\Value\Ship;

class Details
{
    public function __construct(
        /** @var non-negative-int */
        public int $current {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('current cannot be negative');
                }
                $this->current = $value;
            }
        },
        /** @var non-negative-int */
        public int $capacity {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('capacity cannot be negative');
                }
                $this->capacity = $value;
            }
        },
        /** @var non-negative-int */
        public int $required {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('required cannot be negative');
                }
                $this->required = $value;
            }
        },
        public readonly Ship\Crew\Rotation $rotation,
        /** @var non-negative-int */
        public int $morale {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('morale cannot be negative');
                }
                $this->morale = $value;
            }
        },
        /** @var non-negative-int */
        public int $wages {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('wages cannot be negative');
                }
                $this->wages = $value;
            }
        },
    )
    {
    }
}
