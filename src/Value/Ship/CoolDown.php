<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Trait\MapFromArray;

class CoolDown
{
    use MapFromArray;

    public function __construct(
        public readonly string $shipSymbol, // todo proper object?
        /** @var non-negative-int */
        public readonly int $totalSeconds,
        /** @var non-negative-int */
        public readonly int $remainingSeconds,
        public readonly ?\DateTimeImmutable $expiration = null,
    )
    {
    }
}
