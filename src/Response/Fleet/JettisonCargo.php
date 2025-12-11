<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Ship\CargoDetails;

class JettisonCargo extends Base
{
    public function __construct(
        public CargoDetails $cargo,
    ) {
    }
}
