<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;

class ShipCoolDown extends Base
{
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
