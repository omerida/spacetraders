<?php

namespace Phparch\SpaceTraders\Value;

class FactionSymbol
{
    public function __construct(
        public readonly string $faction
    ) {
        // @todo check against whitelist?
    }

    public function __toString(): string
    {
        return $this->faction;
    }
}
