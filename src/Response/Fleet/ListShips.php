<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Base;

class ListShips extends Base
{
    public function __construct(
        /** @var \Phparch\SpaceTraders\Value\Ship[] */
        public array $ships,
    ) {
    }
}
