<?php

namespace Phparch\SpaceTraders\Value;

class SystemTrait
{
    public function __construct(
        /** @var non-empty-string */
        public readonly string $symbol,
        /** @var non-empty-string */
        public readonly string $name,
        /** @var non-empty-string */
        public readonly string $description,
    ) {
    }
}
