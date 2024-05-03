<?php

namespace Phparch\SpaceTraders\Value;

final class Orbital
{
    public function __construct(
        /** @param non-empty-string */
        public readonly string $symbol,
    ) {}

}