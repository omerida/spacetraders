<?php

namespace Phparch\SpaceTraders\Value;

class ShipMountRequirements
{
    public function __construct(
        public readonly int $crew,
        public readonly int $power,
    )
    {
    }
}
