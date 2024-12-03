<?php

namespace Phparch\SpaceTraders\Value;

class ShipOrigin
{
    public function __construct(
        public readonly string $symbol, //enum
        public readonly string $type,
        public readonly SystemSymbol $systemSymbol,
        public int $x,
        public int $y,
    )
    {
    }
}
