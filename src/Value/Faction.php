<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Faction\FactionTrait;

class Faction
{
    use MapFromArray;

    /**
     * @param FactionTrait[] $traits
     */
    public function __construct(
        public readonly string $symbol,
        public readonly string $name,
        public readonly string $description,
        public readonly string $headquarters,
        public readonly array $traits,
        public readonly bool $isRecruiting,
    )
    {
    }
}
