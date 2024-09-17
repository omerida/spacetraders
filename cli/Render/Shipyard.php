<?php

namespace Phparch\SpaceTradersCLI\Render;

use DateTimeInterface;
use \Phparch\SpaceTraders\Value;

class Shipyard extends AbstractRenderer
{
    public function __construct(
        public Value\Shipyard $shipyard,
    ) {
    }

    public function output(): string
    {
        $this->sprintf(
            '<:BLU:>SHIPYARD SYMBOL:<:DEF:> %s',
            $this->shipyard->symbol,
        );
        $this->sprintf(
            '<:BLU:>Modification Fee:<:DEF:> %d',
            $this->shipyard->modificationsFee,
        );
        $this->newline();
        $this->heading("SHIP TYPES");
        if ($this->shipyard->shipTypes) {
            foreach ($this->shipyard->shipTypes as $i => $type) {
                $this->sprintf(
                    "<:GRN:>%d. %s",
                    $i + 1,
                    $type->type,
                );
            }
        } else {
            $this->writeln('None', '');
        }
        $this->newline();

        $this->heading("TRANSACTIONS");
        if ($this->shipyard->transactions) {
            foreach ($this->shipyard->transactions as $i => $tr) {
                $this->sprintf(
                    "<:GRN:>%d. %s %s <:YEL:>%d<:DEF:> %s %s",
                    $i + 1,
                    $tr->symbol,
                    $tr->shipSymbol,
                    $tr->shipType,
                    $tr->price,
                    $tr->agentSymbol.
                    $tr->timestamp->format(DateTimeInterface::ATOM),
                );
            }
            $this->newline();
        } else {
            $this->writeln('None', '');
        }


        $this->heading("SHIPS");
        if ($this->shipyard->ships) {
            foreach ($this->shipyard->ships as $i => $ship) {
                $sub = new Shipyard\Ship($ship);
                $lines = $sub->output();

                $this->writeln($lines);
            }
            $this->newline();
        } else {
            $this->writeln('None', '');
        }


        return parent::output();
    }
}