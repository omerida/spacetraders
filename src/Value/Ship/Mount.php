<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Ship\Mount\Deposit;
use Phparch\SpaceTraders\Value\Ship\Mount\Requirements;

class Mount
{
    use MapFromArray;

    public function __construct(
        public readonly Mount\Symbol $symbol,
        public readonly string $name,
        public readonly string $description,
        public readonly Requirements $requirements,
        public readonly int $strength,
        /**
         * @var Deposit[]
         */
        public readonly array $deposits = [],
    ) {
    }
}
