<?php

namespace Phparch\SpaceTraders\Value;

final class Orbital
{
    public function __construct(
        /** @var non-empty-string */
        public readonly string $symbol,
    ) {
    }
}
