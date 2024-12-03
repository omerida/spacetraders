<?php

namespace Phparch\SpaceTraders\Value;

class RegistrationInfo
{
    public function __construct(
        public readonly string $name,
        public readonly FactionSymbol $factionSymbol,
        public readonly string $role, // enum
    )
    {
    }
}
