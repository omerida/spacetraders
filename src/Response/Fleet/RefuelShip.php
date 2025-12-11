<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Agent;
use Phparch\SpaceTraders\Value\Ship\Fuel;
use Phparch\SpaceTraders\Value\Ship\RefuelTransaction;

class RefuelShip
{
    use MapFromArray;

    public function __construct(
        public Agent $agent,
        public Fuel $fuel,
        public RefuelTransaction $transaction,
    ) {
    }
}
