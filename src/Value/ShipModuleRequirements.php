<?php

namespace Phparch\SpaceTraders\Value;

class ShipModuleRequirements
{
    public function __construct(
        public readonly int $crew,
        public readonly int $power,
        public readonly int $slots,
    ) {
    }
}
