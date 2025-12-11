<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Trait\MapFromArray;

class NavPatch
{
    use MapFromArray;

    public function __construct(
        public readonly Nav $nav,
        public readonly Fuel $fuel,
        /** @var array<string, string> */
        public readonly array $events,
    ) {
    }
}
