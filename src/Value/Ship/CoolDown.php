<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Trait\MapFromArray;

class CoolDown
{
    use MapFromArray;

    public function __construct(
        public readonly string $shipSymbol, // todo proper object?
        /** @var non-negative-int */
        public int $totalSeconds {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('totalSeconds cannot be negative');
                }
                $this->totalSeconds = $value;
            }
        },
        /** @var non-negative-int */
        public int $remainingSeconds {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('remainingSeconds cannot be negative');
                }
                $this->remainingSeconds = $value;
            }
        },
        public readonly ?\DateTimeImmutable $expiration = null,
    )
    {
    }
}
