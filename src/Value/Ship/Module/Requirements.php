<?php

namespace Phparch\SpaceTraders\Value\Ship\Module;

class Requirements
{
    public function __construct(
        /** @var non-negative-int */
        public readonly int $crew,
        /** @var non-negative-int */
        public readonly int $power,
        /** @var non-negative-int */
        public readonly int $slots,
    ) {
    }
}
