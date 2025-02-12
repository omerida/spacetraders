<?php

namespace Phparch\SpaceTraders\Value\Shipyard;

use Phparch\SpaceTraders\Value\WaypointSymbol;
use Phparch\SpaceTraders\Value\Ship;

class Transaction
{
    public function __construct(
        public WaypointSymbol $waypointSymbol,
        public string $shipSymbol,
        public Ship\Type $shipType,
        /** @var non-negative-int */
        public int $price,
        public string $agentSymbol,
        public readonly \DateTimeImmutable $timestamp,
    ) {
    }
}
