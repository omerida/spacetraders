<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Agent;
use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Market\Transaction;
use Phparch\SpaceTraders\Value\Ship\CargoDetails;

class SellCargo
{
    use MapFromArray;

    public function __construct(
        public Agent $agent,
        public CargoDetails $cargo,
        public Transaction $transaction,
    ) {
    }
}
