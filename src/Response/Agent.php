<?php

namespace Phparch\SpaceTraders\Response;

class Agent extends Base
{
    public function __construct(
        /** @var non-empty-string */
        public readonly string $accountId,
        /** @var non-empty-string */
        public readonly string $symbol,
        /** @var non-empty-string */
        public readonly string $headquarters,
        public readonly int $credits,
        /** @var non-empty-string */
        public readonly string $startingFaction,
        /** @var non-negative-int */
        public readonly int $shipCount
    ) {
    }
}
