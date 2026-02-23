<?php

namespace Phparch\SpaceTraders\Value;

class FuelConsumed
{
    public function __construct(
        /** @var non-negative-int */
        public int $amount {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('amount cannot be negative');
                }
                $this->amount = $value;
            }
        },
        public \DateTimeImmutable $timestamp,
    )
    {
    }
}
