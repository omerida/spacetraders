<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Goods;

class CargoDetails extends Base
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
