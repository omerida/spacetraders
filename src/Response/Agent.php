<?php

namespace Phparch\SpaceTraders\Response;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Waypoint;

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
