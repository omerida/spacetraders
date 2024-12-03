<?php

namespace Phparch\SpaceTradersCLI\Render;

class Agent extends AbstractRenderer
{
    public function __construct(
        public \Phparch\SpaceTraders\Response\Agent $agent,
    )
    {
    }

    public function output(): string
    {
        $this->heading("AGENT");
        $this->sprintf(
            '<:BLU:>AGENT: <:DEF:> %s / <:YEL:>%s',
            $this->agent->symbol,
            $this->agent->headquarters
        );
        $this->sprintf(
            '<:BLU:>CREDITS: <:DEF:> <:RED:>%s',
            number_format($this->agent->credits)
        );
        $this->sprintf(
            '<:BLU:>SHIP COUNT: <:DEF:> <:RED:>%s',
            number_format($this->agent->shipCount)
        );
        $this->newline();
        return parent::output();
    }
}
