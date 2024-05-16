<?php

namespace Phparch\SpaceTraders\Value;

class ShipMount {
    public function __construct(
        public readonly string $symbol, // todo use an enum
        public readonly string $name,
        public readonly string $description,
        public readonly int $strength,
        public readonly ShipMountRequirements $requirements,
    ) {
    }
}