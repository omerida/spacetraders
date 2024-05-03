<?php

namespace Phparch\SpaceTraders\Response\My;

use Phparch\SpaceTraders\Response\Base;

class Agent extends Base
{
    public function __construct(
        /** @param non-empty-string */
        public readonly string $accountId,
        /** @param non-empty-string */
        public readonly string $symbol,
        /** @param non-empty-string */
        public readonly string $headquarters,
        public readonly int $credits,
        /** @param non-empty-string */
        public readonly string $startingFaction,
        public readonly int $shipCount
    ) {}
}