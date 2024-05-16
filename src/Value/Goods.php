<?php

namespace Phparch\SpaceTraders\Value;

class Goods {

    public function __construct(
        public readonly string $symbol, // enum
        public readonly string $name,
        public readonly string $description,
        /** @var non-negative-int */
        public readonly int $units
    )
    {
    }
}