<?php

namespace Phparch\SpaceTradersCLI\Render\Ship;

use Phparch\SpaceTraders\Value\ShipCoolDown;
use Phparch\SpaceTradersCLI\Render\AbstractRenderer;

class Cooldown extends AbstractRenderer
{
    public function __construct(
        public ShipCoolDown $cooldown,
    ) {
    }

    public function output(): string
    {
        $this->heading('COOLDOWN');
        $this->sprintf(
            '<:BLU:>SHIP: <:DEF:> %s',
            $this->cooldown->shipSymbol
        );
        $this->sprintf(
            '<:BLU:>COOLDOWN: <:DEF:> %s of %s',
            $this->cooldown->remainingSeconds,
            $this->cooldown->totalSeconds,
        );
        $this->sprintf(
            '<:RED:>EXPIRES: %s',
            $this->formatDate($this->cooldown->expiration)
        );
        $this->newline();
        return parent::output();
    }
}
