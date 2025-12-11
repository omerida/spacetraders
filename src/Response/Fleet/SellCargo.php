<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Agent;
use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Market\Transaction;
use Phparch\SpaceTraders\Value\Ship\CargoDetails;

class SellCargo extends Base
{
    public function __construct(
        public Agent $agent,
        public CargoDetails $cargo,
        public Transaction $transaction,
    ) {
    }
}
