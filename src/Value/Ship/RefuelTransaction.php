<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\Goods;
use Phparch\SpaceTraders\Value\TransactionType;
use Phparch\SpaceTraders\Value\Waypoint;

class RefuelTransaction
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
        /** @var non-negative-int */
        public int $pricePerUnit {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('pricePerUnit cannot be negative');
                }
                $this->pricePerUnit = $value;
            }
        },
        /** @var non-negative-int */
        public int $totalPrice {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('totalPrice cannot be negative');
                }
                $this->totalPrice = $value;
            }
        },
        public readonly \DateTimeImmutable $timestamp,
    )
    {
    }
}
