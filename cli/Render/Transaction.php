<?php

namespace Phparch\SpaceTradersCLI\Render;

class Transaction extends AbstractRenderer
{
    public function __construct(
        public \Phparch\SpaceTraders\Value\Shipyard\Transaction $txn,
    )
    {
    }

    public function output(): string
    {
        $this->heading("TRANSACTION");
        $this->sprintf(
            '<:BLU:>WAYPOINT: <:DEF:> <:YEL:>%s',
            $this->txn->waypointSymbol,
        );
        $this->sprintf(
            '<:BLU:>SHIP & TYPE: <:DEF:> <:RED:>%s/%s',
            $this->txn->shipSymbol,
            $this->txn->shipType->value
        );
        $this->sprintf(
            '<:BLU:>PRICE: <:DEF:> <:YEL:>%s',
            number_format($this->txn->price),
        );

        $this->sprintf(
            '<:BLU:>AGENT & TIME: <:DEF:>%s, %s',
            $this->txn->agentSymbol,
            $this->txn->timestamp->format(DATE_COOKIE)
        );
        $this->newline();
        return parent::output();
    }
}
