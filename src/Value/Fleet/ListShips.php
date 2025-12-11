<?php

namespace Phparch\SpaceTraders\Value\Fleet;

use Phparch\SpaceTraders\Trait\MapFromArray;

class ListShips
{
    use MapFromArray;

    public function __construct(
        /** @var \Phparch\SpaceTraders\Value\Ship[] */
        public array $ships,
    ) {
    }
}
