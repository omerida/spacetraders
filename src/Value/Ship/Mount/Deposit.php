<?php

namespace Phparch\SpaceTraders\Value\Ship\Mount;

use Phparch\SpaceTraders\Value\Ship;

class Deposit
{
    public function __construct(
        public readonly Ship\Mount\DepositSymbol $deposit,
    ) {
    }
}
