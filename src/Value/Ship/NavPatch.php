<?php

namespace Phparch\SpaceTraders\Value\Ship;

use Phparch\SpaceTraders\Response\Base;

class NavPatch extends Base
{
    public function __construct(
        public readonly Nav $nav,
        public readonly Fuel $fuel,
        /** @var array<string, string> */
        public readonly array $events,
    ) {
    }
}
