<?php

namespace Phparch\SpaceTradersCLI\Render\Market;

use Phparch\SpaceTraders\Value;
use Phparch\SpaceTradersCLI\Render\AbstractRenderer;

class Transaction extends AbstractRenderer
{
    public function __construct(
        public readonly Value\Market\Transaction $transaction,
    )
    {
    }

    public function output(): string
    {
        $this->heading("TRANSACTION");
        $this->passthru(new TransactionsList([$this->transaction]));
        $this->newline();
        return parent::output();
    }
}
