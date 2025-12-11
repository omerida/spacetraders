<?php

namespace Phparch\SpaceTraders\Value\Ship\Extraction;

class Details
{
    public function __construct(
        public readonly string $shipSymbol, // todo proper object?
        public readonly YieldResult $yield,
    ) {
    }
}
