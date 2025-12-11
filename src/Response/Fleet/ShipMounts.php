<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Trait\MapFromArray;

class ShipMounts
{
    use MapFromArray;

    public function __construct(
        /** @var \Phparch\SpaceTraders\Value\Ship\Mount[] */
        public array $mounts,
    ) {
    }
}
