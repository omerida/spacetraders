<?php

namespace Phparch\SpaceTraders\Value\System;

class Chart
{
    public function __construct(
        public readonly string $waypointSymbol,
        public readonly string $submittedBy,
        public readonly \DateTimeImmutable $submittedOn,
    ) {
    }
}
