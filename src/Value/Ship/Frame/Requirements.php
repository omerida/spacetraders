<?php

namespace Phparch\SpaceTraders\Value\Ship\Frame;

class Requirements
{
    public function __construct(
        /** @var non-negative-int */
        public readonly int $power,
        /** @var non-negative-int */
        public readonly int $crew,
        /** @var null|non-negative-int */
        public readonly ?int $slots = null,
    )
    {
    }
}
