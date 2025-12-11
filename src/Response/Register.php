<?php

namespace Phparch\SpaceTraders\Response;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value;

class Register
{
    use MapFromArray;

    public function __construct(
        public readonly Agent $agent,
        public readonly Value\Contract $contract,
        public readonly Value\Faction $faction,
        public readonly Value\Ship $ship,
        public readonly string $token,
    ) {
    }
}
