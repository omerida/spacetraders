<?php

namespace Phparch\SpaceTraders\Value;

class ShipReactorRequirements
{
    public function __construct(
        /** @var non-negative-int */
        public readonly int $crew,
        public readonly ?int $power = null,
        public readonly ?int $slots = null,
    )
    {
    }
}