<?php

namespace Phparch\SpaceTraders\Presenter;

use Phparch\SpaceTradersRest\Value\Contract;

class ContractSummary
{
    public function __construct(
        private Contract $contract,
    ) {
    }

    public function getContractID(): string {
        return $this->contract->id;
    }

    public function getShortContractID(): string {
        return substr($this->contract->id, 0, 10);
    }

    public function getSummary(): string {
        $summary = "";
        if ($this->contract->type->value === 'PROCUREMENT') {
            foreach ($this->contract->terms->deliver as $deliver) {
                $summary .= sprintf(
                    "Deliver %s to %s (%d/%d units).",
                    $deliver->tradeSymbol->value,
                    $deliver->destinationSymbol,
                    $deliver->unitsFulfilled,
                    $deliver->unitsRequired
                );
            }
        }
        return $summary;
    }
}
