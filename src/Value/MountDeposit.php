<?php

namespace Phparch\SpaceTraders\Value;

class MountDeposit
{
    public function __construct(
        public readonly Ship\MountDepositSymbol $deposit,
    ) {
    }
}
