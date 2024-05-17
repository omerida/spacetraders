<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;

class ShipMount extends Base {
    public function __construct(
        public readonly string $symbol, // todo use an enum
        public readonly string $name,
        public readonly string $description,
        public readonly int $strength,
        /**
         * @var string[]|null
         */
        public readonly ?array $deposits,
        public readonly ShipMountRequirements $requirements,
    ) {
    }
}