<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Ship\Mount\Deposit;
use Phparch\SpaceTraders\Value\Ship\Mount\Requirements;

class Mount extends Base
{
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
