<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\Goods\Symbol;

class ExtractionYield
{
    public function __construct(
        public readonly Symbol $symbol,
        public readonly int $units,
    ) {
    }
}
