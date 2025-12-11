<?php

namespace Phparch\SpaceTraders\Value\Waypoint;

class Faction
{
    public function __construct(
        public readonly string $symbol
    ) {
        // @todo check against whitelist?
    }

    public function __toString(): string
    {
        return $this->symbol;
    }
}
