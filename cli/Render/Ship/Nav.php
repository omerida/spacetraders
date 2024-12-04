<?php

namespace Phparch\SpaceTradersCLI\Render\Ship;

use Phparch\SpaceTradersCLI\Render\AbstractRenderer;

class Nav extends AbstractRenderer
{
    public function __construct(
        public \Phparch\SpaceTraders\Value\Ship\Nav $nav,
    )
    {
    }

    public function output(): string
    {
        $this->heading("SHIP NAV");
        $this->sprintf(
            '<:BLU:>SYSTEM & WAYPOINT: <:DEF:> %s / <:YEL:>%s',
            $this->nav->systemSymbol,
            $this->nav->waypointSymbol,
        );
        $this->sprintf(
            '<:BLU:>STATUS: <:DEF:> %s'
            . '   <:BLU:>FLIGHT MODE: <:DEF:> %s',
            $this->nav->status,
            $this->nav->flightMode,
        );
        $this->newline();
        return parent::output();
    }
}
