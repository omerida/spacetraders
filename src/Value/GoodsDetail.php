<?php

namespace Phparch\SpaceTraders\Value;

class GoodsDetail
{
    public function __construct(
        public readonly GoodsSymbol $symbol,
        public readonly string $name,
        public readonly string $description,
    )
    {
    }
}
