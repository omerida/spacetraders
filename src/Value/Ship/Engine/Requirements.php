<?php

namespace Phparch\SpaceTraders\Value\Ship\Engine;

class Requirements
{
    public function __construct(
        /** @var non-negative-int */
        public int $power {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('power cannot be negative');
                }
                $this->power = $value;
            }
        },
        /** @var non-negative-int */
        public int $crew {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('crew cannot be negative');
                }
                $this->crew = $value;
            }
        },
        /** @var null|non-negative-int */
        public readonly ?int $slots = null,
    )
    {
    }
}
