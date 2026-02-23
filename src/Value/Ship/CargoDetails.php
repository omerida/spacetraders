<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Goods;

class CargoDetails
{
    use MapFromArray;

    public function __construct(
        /** @var non-negative-int */
        public int $capacity {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('capacity cannot be negative');
                }
                $this->capacity = $value;
            }
        },
        /** @var non-negative-int */
        public int $units {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('units cannot be negative');
                }
                $this->units = $value;
            }
        },
        /** @var Goods[] */
        public readonly array $inventory,
    )
    {
    }
}
