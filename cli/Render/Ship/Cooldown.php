<?php

namespace Phparch\SpaceTradersCLI\Render\Ship;

use Phparch\SpaceTraders\Value\Ship;
use Phparch\SpaceTradersCLI\Render\AbstractRenderer;

class Cooldown extends AbstractRenderer
{
    public function __construct(
        public Ship\CoolDown $cooldown,
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
        if ($this->cooldown->expiration) {
            $this->sprintf(
                '<:RED:>EXPIRES: %s',
                $this->formatDate($this->cooldown->expiration)
            );
        }
        $this->newline();
        return parent::output();
    }
}
