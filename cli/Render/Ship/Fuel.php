<?php

namespace Phparch\SpaceTradersCLI\Render\Ship;

use Phparch\SpaceTradersCLI\Render\AbstractRenderer;

class Fuel extends AbstractRenderer
{
    public function __construct(
        public \Phparch\SpaceTraders\Value\ShipFuel $fuel,
    ) {
    }

    public function output(): string
    {
        $this->heading("FUEL");
        $this->sprintf(
            '<:BLU:>CURRENT: <:DEF:> %s / <:YEL:>%s',
            $this->fuel->current,
            $this->fuel->capacity,
        );
        $this->sprintf(
            '<:BLU:>CONSUMED: <:DEF:> %s, %s',
            $this->fuel->consumed->amount,
            $this->formatDate($this->fuel->consumed->timestamp),
        );
        $this->newline();
        return parent::output();
    }
}
