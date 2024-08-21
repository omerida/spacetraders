<?php

namespace Phparch\SpaceTraders\Value;

class ShipDestination
{
    public function __construct(
        public readonly string $symbol,
        public readonly string $type, // enum?
        public readonly SystemSymbol $systemSymbol,
        public int $x,
        public int $y,
    )
    {
    }
}