<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;

class ShipCargoDetails extends Base
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
