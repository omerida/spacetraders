<?php

namespace Phparch\SpaceTraders\Value\Fleet;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Ship\CargoDetails;

class JettisonCargo
{
    use MapFromArray;

    public function __construct(
        public CargoDetails $cargo,
    ) {
    }
}
