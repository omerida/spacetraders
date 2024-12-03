<?php

namespace Phparch\SpaceTraders\Value;

class FuelConsumed
{
    public function __construct(
        /** @var non-negative-int */
        public readonly int $amount,
        public \DateTimeImmutable $timestamp,
    )
    {
    }
}
