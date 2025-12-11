<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Goods;

class CargoDetails
{
    use MapFromArray;

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
