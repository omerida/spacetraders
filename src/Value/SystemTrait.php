<?php

namespace Phparch\SpaceTraders\Value;

class SystemTrait
{
    public function __construct(
        /** @param non-empty-string */
        public readonly string $symbol,
        /** @param non-empty-string */
        public readonly string $name,
        /** @param non-empty-string */
        public readonly string $description,
    )
    {
    }
}