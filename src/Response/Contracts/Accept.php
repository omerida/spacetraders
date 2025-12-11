<?php

namespace Phparch\SpaceTraders\Response\Contracts;

use Phparch\SpaceTraders\Response\Agent;
use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Contract;

class Accept
{
    use MapFromArray;

    public function __construct(
        public Contract $contract,
        public Agent $agent,
    ) {
    }
}
