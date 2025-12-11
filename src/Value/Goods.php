<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Value\Goods\Symbol;

class Goods
{
    public function __construct(
        public readonly Symbol $symbol,
        public readonly string $name,
        public readonly string $description,
        /** @var non-negative-int */
        public readonly int $units
    )
    {
    }
}
