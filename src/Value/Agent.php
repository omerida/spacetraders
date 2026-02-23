<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Trait\MapFromArray;

class Agent
{
    use MapFromArray;

    public function __construct(
        /** @var non-empty-string */
        public string $accountId {
            set {
                if (empty(trim($value))) {
                    throw new \InvalidArgumentException('accountId cannot be empty');
                }
                $this->accountId = $value;
            }
        },
        /** @var non-empty-string */
        public string $symbol {
            set {
                if (empty(trim($value))) {
                    throw new \InvalidArgumentException('symbol cannot be empty');
                }
                $this->symbol = $value;
            }
        },
        public readonly Waypoint\Symbol $headquarters,
        public readonly int $credits,
        /** @var non-empty-string */
        public string $startingFaction {
            set {
                if (empty(trim($value))) {
                    throw new \InvalidArgumentException('startingFaction cannot be empty');
                }
                $this->startingFaction = $value;
            }
        },
        /** @var non-negative-int */
        public int $shipCount {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('shipCount cannot be negative');
                }
                $this->shipCount = $value;
            }
        }
    ) {
    }
}
