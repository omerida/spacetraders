<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;

class Faction extends Base
{
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
