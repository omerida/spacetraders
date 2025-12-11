<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Value\Ship\Module\Requirements;

class Module
{
    public function __construct(
        public readonly Module\Symbol $symbol,
        public readonly string $name,
        public readonly string $description,
        public readonly Requirements $requirements,
        public readonly ?int $capacity = null,
    ) {
    }
}
