<?php

namespace Phparch\SpaceTradersCLI\Render;

class Agent extends AbstractRenderer
{
    public function __construct(
        public \Phparch\SpaceTraders\Value\Agent $agent,
    )
    {
    }

    public function output(): string
    {
        $this->heading("AGENT");
        $this->sprintf(
            '<:BLU:>AGENT & HQ: <:DEF:> %s / <:YEL:>%s' .
            '   <:BLU:>STARTING FACTION: <:DEF:> %s',
            $this->agent->symbol,
            $this->agent->headquarters,
            $this->agent->startingFaction
        );
        $this->sprintf(
            '<:BLU:>CREDITS: <:DEF:> %s'
            . '   <:BLU:>SHIP COUNT: <:DEF:> %s',
            number_format($this->agent->credits),
            number_format($this->agent->shipCount)
        );
        $this->newline();
        return parent::output();
    }
}
