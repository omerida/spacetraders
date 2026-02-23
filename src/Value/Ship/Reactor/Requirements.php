<?php

namespace Phparch\SpaceTraders\Value\Ship\Reactor;

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
        public readonly ?int $power = null,
        public readonly ?int $slots = null,
    )
    {
    }
}
