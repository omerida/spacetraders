<?php

namespace Phparch\SpaceTraders\Response;

use Phparch\SpaceTraders\Value\Waypoint\Symbol;

class Agent extends Base
{
    public function __construct(
        /** @var non-empty-string */
        public readonly string $accountId,
        /** @var non-empty-string */
        public readonly string $symbol,
        public readonly Symbol $headquarters,
        public readonly int $credits,
        /** @var non-empty-string */
        public readonly string $startingFaction,
        /** @var non-negative-int */
        public readonly int $shipCount
    ) {
    }
}
