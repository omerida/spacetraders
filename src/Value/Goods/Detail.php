<?php

namespace Phparch\SpaceTraders\Value\Goods;

class Detail
{
    public function __construct(
        public readonly Symbol $symbol,
        public readonly string $name,
        public readonly string $description,
    )
    {
    }
}
