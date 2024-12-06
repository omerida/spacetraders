<?php

namespace Phparch\SpaceTraders\Value;

class ShipModule
{
    public function __construct(
        public readonly Ship\ModuleSymbol $symbol,
        public readonly string $name,
        public readonly string $description,
        public readonly ShipModuleRequirements $requirements,
        public readonly ?int $capacity = null,
    ) {
    }
}
