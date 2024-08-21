<?php

namespace Phparch\SpaceTraders\Value;

class Route
{
    public function __construct(
        public readonly ShipDestination $destination,
        public readonly ShipOrigin $origin,
        public readonly \DateTimeImmutable $departureTime,
        public readonly \DateTimeImmutable $arrival,
    )
    {
    }
}