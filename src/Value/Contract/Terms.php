<?php

namespace Phparch\SpaceTraders\Value\Contract;

use Phparch\SpaceTraders\Value\PaymentTerms;

class Terms
{
    public function __construct(
        public readonly \DateTimeImmutable $deadline,
        public readonly PaymentTerms $payment,
        /** @var Deliver[] */
        public readonly array $deliver,
    )
    {
    }
}
