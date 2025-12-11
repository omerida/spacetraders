<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Value\Faction\Symbol;

class RegistrationInfo
{
    public function __construct(
        public readonly string $name,
        public readonly Symbol $factionSymbol,
        public readonly Ship\RegisteredRole $role,
    )
    {
    }
}
