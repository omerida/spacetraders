<?php

namespace Phparch\SpaceTraders\Value;

class SystemChart
{
    public function __construct(
        public readonly string $waypointSymbol,
        public readonly string $submittedBy,
        public readonly \DateTimeImmutable $submittedOn,
    ) {
    }
}
