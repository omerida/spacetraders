<?php

namespace Phparch\SpaceTraders\Value\Ship\Mount;

class Requirements
{
    public function __construct(
        /** @var non-negative-int */
        public int $crew {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('crew cannot be negative');
                }
                $this->crew = $value;
            }
        },
        /** @var non-negative-int */
        public int $power {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('power cannot be negative');
                }
                $this->power = $value;
            }
        },
    )
    {
    }
}
