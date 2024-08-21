<?php

namespace Phparch\SpaceTradersCLI\Render;

class Contract implements RenderInterface {

    public function __construct(
        public \Phparch\SpaceTraders\Value\Contract $contract,
    ) {}

    public function output(): string {
        $out = [];
        $out[] = (string) $this->contract->factionSymbol . ' / ' . $this->contract->id;
        $out[] = sprintf(
            "TYPE: %-15s    ACCEPTED? %s    FULFILLED? %s",
            $this->contract->type,
            $this->contract->accepted ? "Yes" : "No",
            $this->contract->fulfilled ? "Yes" : "No",
        );
        $out[] = PHP_EOL . sprintf(
            "Deliver by %s.\nReceive %s on acceptance and %s on fulfillment.",
            $this->contract->terms->deadline->format(DATE_COOKIE),
            number_format($this->contract->terms->payment->onAccepted),
            number_format($this->contract->terms->payment->onFulfilled),
        ) . PHP_EOL;

        $out[] = "Resource(s)              Destination  Required  Fulfilled";
        $out[] = "----------------------------------------------------------";
        foreach ($this->contract->terms->deliver as $deliver) {
            $out[] = sprintf(
                "%-25s %-12s %8d %10d",
                $deliver->tradeSymbol,
                $deliver->destinationSymbol,
                $deliver->unitsRequired,
                $deliver->unitsFulfilled,
            );
        }


        $out[] = PHP_EOL . "  EXPIRES: " . $this->contract->expiration->format(DATE_COOKIE);
        $out[] = "ACCEPT BY: " . $this->contract->deadlineToAccept->format(DATE_COOKIE);

        if (!$this->contract->accepted) {
            $out[] = PHP_EOL . "To accept:";
            $out[] = PHP_EOL . "  spacetraders contracts accept " . $this->contract->id;
        }
        return implode(PHP_EOL, $out) . PHP_EOL;
    }
}