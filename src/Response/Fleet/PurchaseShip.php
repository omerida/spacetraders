<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Agent;
use Phparch\SpaceTraders\Value\Ship;
use Phparch\SpaceTraders\Value\Shipyard\Transaction;

class PurchaseShip
{
    use MapFromArray;

    public function __construct(
        public Agent $agent,
        public Ship $ship,
        public Transaction $transaction,
    ) {
    }
}
