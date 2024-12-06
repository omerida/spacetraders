<?php

namespace Phparch\SpaceTraders\Value\Shipyard;

use Phparch\SpaceTraders\Value\Ship;

class ShipType
{
    public function __construct(
        public readonly Ship\Type $type,
    ) {
    }
}
