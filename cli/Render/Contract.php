<?php

namespace Phparch\SpaceTradersCLI\Render;

class Contract extends AbstractRenderer
{
    public function __construct(
        public \Phparch\SpaceTraders\Value\Contract $contract,
    ) {
    }

    public function output(): string
    {
        $this->sprintf(
            '<:BLU:>%s<:DEF:> / <:RED:>%s ',
            (string) $this->contract->factionSymbol,
            $this->contract->id
        );
        $this->sprintf(
            $this->blue('TYPE:') . $this->yellow(' %-25s')
            . '    ' . $this->blue('ACCEPTED?') . ' ' . $this->yellow('%s')
            . '    ' . $this->blue('FULFILLED?') . ' ' . $this->yellow('%s'),
            $this->contract->type,
            $this->contract->accepted ? "Yes" : "No",
            $this->contract->fulfilled ? "Yes" : "No",
        );
        $this->newline();
        $this->sprintf(
            "Deliver by " . $this->red('%s'),
            $this->contract->terms->deadline->format(DATE_COOKIE),
        );
        $this->sprintf(
            "Receive " . $this->yellow('%s') . ' on acceptance and '
            . $this->yellow('%s') . ' on fulfillment.',
            number_format($this->contract->terms->payment->onAccepted),
            number_format($this->contract->terms->payment->onFulfilled),
        );

        $this->newline();
        $this->writeln(
            $this->blue("Resource(s)              Destination   Required  Fulfilled"),
            $this->blue("----------------------------------------------------------"),
        );
        foreach ($this->contract->terms->deliver as $deliver) {
            $this->sprintf(
                "%-25s %-12s %8d %10d",
                $deliver->tradeSymbol,
                $deliver->destinationSymbol,
                $deliver->unitsRequired,
                $deliver->unitsFulfilled,
            );
        }

        $this->newline();
        $this->writeln(
            $this->blue("  EXPIRES: ")
            . $this->red($this->contract->expiration->format(DATE_COOKIE)),
            $this->blue("ACCEPT BY: ")
            . $this->yellow($this->contract->deadlineToAccept->format(DATE_COOKIE))
        );

        if (!$this->contract->accepted) {
            $this->heading("To accept:");
            $this->writeln(
                "  spacetraders contracts accept " . $this->contract->id
            );
        }
        return parent::output();
    }
}
