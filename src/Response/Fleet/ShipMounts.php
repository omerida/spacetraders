<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Base;

class ShipMounts extends Base
{
    public function __construct(
        /** @var \Phparch\SpaceTraders\Value\ShipMount[] */
        public array $mounts,
    ) {}
}