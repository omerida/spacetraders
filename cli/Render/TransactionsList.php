<?php

namespace Phparch\SpaceTradersCLI\Render;

use Phparch\SpaceTraders\Value\Market\Transaction;

class TransactionsList extends AbstractRenderer
{
    public function __construct(
        /** @param Transaction[] */
        public array $transactions,
    )
    {
    }

    public function output(): string
    {
        $this->sprintf(
            '<:CYN:>%-12s %-20s %-15s %-8s %8s %8s %10s ',
            "Waypoint",
            "Ship",
            "Trade",
            "Type",
            "Units",
            "perUnit",
            "Total",
            "Timestamp"
        );
        foreach ($this->transactions as $i => $txn) {
            $color = $i % 2 ? "" : "<:YEL:>";
            $this->sprintf(
                $color . '%-12s %-20s %-15s %-8s %8s %8s %10s',
                $txn->waypointSymbol,
                $txn->shipSymbol,
                $txn->tradeSymbol->value,
                $txn->type->value,
                $txn->units,
                $txn->pricePerUnit,
                $txn->totalPrice,
                $this->formatDate($txn->timestamp)
            );
        }
        return parent::output();
    }
}
