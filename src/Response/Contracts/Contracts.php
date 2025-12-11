<?php

namespace Phparch\SpaceTraders\Response\Contracts;

use Phparch\SpaceTraders\Trait\MapFromArray;

class Contracts
{
    use MapFromArray;

    public function __construct(
        /** @var list<\Phparch\SpaceTraders\Value\Contract> */
        public array $contracts = [],
    ) {
    }
}
