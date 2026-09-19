<?php

namespace Phparch\SpaceTraders\Presenter;

use Phparch\SpaceTradersRest\Value\Contract;

class ContractSummary
{
    public function __construct(
        private Contract $contract,
    ) {
    }

    public function getSummary(): string {
        $summary = "";
        if ($this->contract->type->value === 'PROCUREMENT') {
            foreach ($this->contract->terms->deliver as $deliver) {
                $summary = "Deliver {$deliver->tradeSymbol->value} ";
                $summary .= "to {$deliver->destinationSymbol}";
                $summary .= " ($deliver->unitsFulfilled / $deliver->unitsRequired units). ";
            }
        }
        return $summary;
    }
}
