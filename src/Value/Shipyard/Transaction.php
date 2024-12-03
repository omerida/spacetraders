<?php

namespace Phparch\SpaceTraders\Value\Shipyard;

use Phparch\SpaceTraders\Value\WaypointSymbol;

class Transaction
{
    public function __construct(
        public WaypointSymbol $waypointSymbol,
        public string $shipSymbol,
        public string $shipType, // enum
        /** @var non-negative-int */
        public int $price,
        public string $agentSymbol,
        public readonly \DateTimeImmutable $timestamp,
    ) {
    }
}
