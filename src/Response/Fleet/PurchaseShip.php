<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Agent;
use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Ship;
use Phparch\SpaceTraders\Value\Shipyard\Transaction;

class PurchaseShip extends Base
{
    public function __construct(
        public Agent $agent,
        public Ship $ship,
        public Transaction $transaction,
    ) {
    }
}
