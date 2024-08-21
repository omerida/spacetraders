<?php

namespace Phparch\SpaceTraders\Value;

class ShipEngineRequirements
{
    public function __construct(
        /** @var non-negative-int */
        public readonly int $power,
        /** @var non-negative-int */
        public readonly int $crew,
        public readonly ?int $slots = null,
    )
    {
    }
}