<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Value\Ship\Destination;
use Phparch\SpaceTraders\Value\Ship\Origin;

class Route
{
    public function __construct(
        public readonly Destination $destination,
        public readonly Origin $origin,
        public readonly \DateTimeImmutable $departureTime,
        public readonly \DateTimeImmutable $arrival,
    )
    {
    }
}
