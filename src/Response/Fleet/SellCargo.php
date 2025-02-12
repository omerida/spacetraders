<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Agent;
use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Market\Transaction;
use Phparch\SpaceTraders\Value\ShipCargoDetails;

class SellCargo extends Base
{
    public function __construct(
        public Agent $agent,
        public ShipCargoDetails $cargo,
        public Transaction $transaction,
    ) {
    }
}
