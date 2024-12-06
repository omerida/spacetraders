<?php

namespace Phparch\SpaceTradersCLI\Render\Ship;

use Phparch\SpaceTradersCLI\Render\AbstractRenderer;

class RefuelTransaction extends AbstractRenderer
{
    public function __construct(
        public \Phparch\SpaceTraders\Value\Ship\RefuelTransaction $txn,
    ) {
    }

    public function output(): string
    {
        $this->heading("REFUEL TRANSACTION");
        $this->sprintf(
            '<:BLU:>WAYPOINT: <:DEF:> %s',
            $this->txn->waypointSymbol,
        );
        $this->sprintf(
            '<:BLU:>INFO: <:DEF:> %s, %s',
            $this->txn->tradeSymbol->value,
            $this->txn->type->value,
        );
        $this->sprintf(
            '<:GRN:>DETAILS: <:DEF:> %s units @ %s per unit. <:GRN:>TOTAL: %s',
            number_format($this->txn->units),
            number_format($this->txn->pricePerUnit),
            number_format($this->txn->totalPrice),
        );
        $this->sprintf(
            '<:YEL:>%s',
            $this->formatDate($this->txn->timestamp),
        );
        $this->newline();
        return parent::output();
    }
}
