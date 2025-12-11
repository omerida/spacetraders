<?php

namespace Phparch\SpaceTraders\Value\Faction;

class Symbol
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
