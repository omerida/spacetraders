<?php

namespace Phparch\SpaceTraders\Value;

class ShipCargoDetails
{
    public function __construct(
        /** @var non-negative-int */
        public readonly int $capacity,
        /** @var non-negative-int */
        public readonly int $units,
        /** @var Goods[] */
        public readonly array $inventory,
    )
    {
    }
}