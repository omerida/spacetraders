<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Agent;
use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Ship\RefuelTransaction;
use Phparch\SpaceTraders\Value\Ship\Fuel;

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
