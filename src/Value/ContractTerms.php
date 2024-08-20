<?php

namespace Phparch\SpaceTraders\Value;

class ContractTerms
{
    public function __construct(
        public readonly \DateTimeImmutable $deadline,
        public readonly PaymentTerms $payment,
        /** @var ContractDeliver[] */
        public readonly array $deliver,
    )
    {
    }
}