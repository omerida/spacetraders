<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\GoodsSymbol;

class ExtractionYield
{
    public function __construct(
        public readonly GoodsSymbol $symbol,
        public readonly int $units,
    ) {
    }
}
