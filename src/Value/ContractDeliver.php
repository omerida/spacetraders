<?php

namespace Phparch\SpaceTraders\Value;

class ContractDeliver
{
    public function __construct(
        public readonly string $tradeSymbol,
        public readonly string $destinationSymbol,
        /** @var non-negative-int */
        public readonly int $unitsRequired,
        /** @var non-negative-int */
        public readonly int $unitsFulfilled,
    )
    {
    }
}