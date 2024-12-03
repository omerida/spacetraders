<?php

namespace Phparch\SpaceTraders\Value\Shipyard;

class ShipType
{
    public function __construct(
        // enum
        public readonly string $type,
    ) {
    }
}
