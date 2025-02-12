<?php

namespace Phparch\SpaceTraders\Value;

class PaymentTerms
{
    public function __construct(
        public readonly int $onAccepted,
        public readonly int $onFulfilled,
    ) {
    }
}
