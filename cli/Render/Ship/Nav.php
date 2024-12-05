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

        $route = $this->nav->route;
        $this->heading("ROUTE");
        $this->sprintf(
            '<:BLU:>DESTINATION: <:DEF:> %s / <:YEL:>%s <:DEF:> (%s, %s)',
            $route->destination->symbol,
            $route->destination->type,
            $route->destination->x,
            $route->destination->y,
        );
        $this->sprintf(
            '<:GRN:>ORIGIN: <:DEF:> %s / <:YEL:>%s <:DEF:> (%s, %s)',
            $route->destination->symbol,
            $route->destination->type,
            $route->destination->x,
            $route->destination->y,
        );

        $this->sprintf(
            '<:YEL:>DEPARTED: <:DEF:> %s',
            $this->formatDate($route->departureTime)
        );
        $this->sprintf(
            '<:GRN:>ARRIVAL: <:DEF:> %s',
            $this->formatDate($route->arrival)
        );

        $now = new \DateTimeImmutable();
        $remaining = $route->arrival->diff($now);

        $this->sprintf(
            '<:YEL:>%s <:DEF:> %s',
            $now < $route->arrival ? "REMAINING TRAVEL TIME:" : "TIME SINCE ARRIVAL:",
            $remaining->format('%d day(s), %h hour(s), %i minute(s)'),
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
