<?php

namespace Phparch\SpaceTraders\Value\Ship;

class ExtractionDetails
{
    public function __construct(
        public readonly string $shipSymbol, // todo proper object?
        public readonly ExtractionYield $yield,
    ) {
    }
}
