<?php

namespace Phparch\SpaceTraders\Response\Contracts;

use Phparch\SpaceTraders\Response\Base;

class Contracts extends Base
{
    public function __construct(
        /** @var list<\Phparch\SpaceTraders\Value\Contract> */
        public array $contracts,
    ) {}
}