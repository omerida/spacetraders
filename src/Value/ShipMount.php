<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\MountDeposit;

class ShipMount extends Base
{
    public function __construct(
        public readonly string $symbol, // todo use an enum
        public readonly string $name,
        public readonly string $description,
        public readonly ShipMountRequirements $requirements,
        public readonly int $strength,
        /**
         * @var MountDeposit[]
         */
        public readonly array $deposits = [],
    ) {
    }
}
