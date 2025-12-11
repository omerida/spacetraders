<?php

namespace Phparch\SpaceTraders\Value\Faction;

class FactionTrait
{
    public function __construct(
        public readonly FactionTraitSymbol $symbol,
        public readonly string $name,
        public readonly string $description,
    )
    {
    }
}
