<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Trait\MapFromArray;

class Agent
{
    use MapFromArray;

    public function __construct(
        /** @var non-empty-string */
        public readonly string $accountId,
        /** @var non-empty-string */
        public readonly string $symbol,
        public readonly Waypoint\Symbol $headquarters,
        public readonly int $credits,
        /** @var non-empty-string */
        public readonly string $startingFaction,
        /** @var non-negative-int */
        public readonly int $shipCount
    ) {
    }
}
