<?php

namespace Phparch\SpaceTraders\Value\Market;

use Phparch\SpaceTraders\Value\Goods;
use Phparch\SpaceTraders\Value\TransactionType;
use Phparch\SpaceTraders\Value\Waypoint;

class Transaction
{
    public function __construct(
        public Waypoint\Symbol $waypointSymbol,
        public string $shipSymbol,
        public Goods\Symbol $tradeSymbol,
        public TransactionType $type,
        /** @var non-negative-int */
        public int $units {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('units cannot be negative');
                }
                $this->units = $value;
            }
        },
        public int $pricePerUnit,
        public int $totalPrice,
        public readonly \DateTimeImmutable $timestamp,
    ) {
    }
}
