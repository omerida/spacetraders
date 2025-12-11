<?php

namespace Phparch\SpaceTraders\Value\Ship\Extraction;

use Phparch\SpaceTraders\Value\Goods\Symbol;

class YieldResult
{
    public function __construct(
        public readonly Symbol $symbol,
        /** @var non-negative-int */
        public readonly int $units,
    ) {
    }
}
