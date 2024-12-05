<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Response\Agent;
use Phparch\SpaceTraders\Value\Ship\RefuelTransaction;
use Phparch\SpaceTraders\Value\ShipFuel;

class RefuelShip extends Base
{
    public function __construct(
        public Agent $agent,
        public ShipFuel $fuel,
        public RefuelTransaction $transaction,
    ) {
    }
}
