<?php

namespace Phparch\SpaceTraders\Response\Contracts;

use Phparch\SpaceTraders\Response\Agent;
use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Contract;

class Accept extends Base
{
    public function __construct(
        public Contract $contract,
        public Agent $agent,
    ) {}
}