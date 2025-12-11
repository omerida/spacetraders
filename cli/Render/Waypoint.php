<?php

namespace Phparch\SpaceTradersCLI\Render;

use Phparch\SpaceTraders\Response;

class Waypoint extends AbstractRenderer
{
    public function __construct(
        public \Phparch\SpaceTraders\Value\Waypoint $waypoint,
    ) {
    }

    public function output(): string
    {
        $this->divider(color: "<:CYN:>");
        $this->sprintf(
            '<:BLU:>SYSTEM SYMBOL:<:DEF:> %s, <:YEL:>%s<:DEF:> (x: %d, y: %d)',
            $this->waypoint->symbol,
            $this->waypoint->type,
            $this->waypoint->x,
            $this->waypoint->y,
        );

        $this->writeln('');

        $this->sprintf(
            '<:BLU:>CHART:<:DEF:> Submitted by <:YEL:>%s<:DEF:> on <:YEL:>%s',
            $this->waypoint->chart->submittedBy,
            $this->waypoint->chart->submittedOn->format(DATE_COOKIE),
        );

        $this->heading("ORBITALS");
        if ($this->waypoint->orbitals) {
            foreach ($this->waypoint->orbitals as $i => $orbital) {
                $this->sprintf(
                    "<:GRN:>%d. %s",
                    $i + 1,
                    $orbital->symbol,
                );
            }
        } else {
            $this->writeln('None', '');
        }

        $this->heading("TRAITS");
        if ($this->waypoint->traits) {
            foreach ($this->waypoint->traits as $i => $trait) {
                $this->sprintf(
                    "<:GRN:>%d. %s (%s)",
                    $i + 1,
                    $trait->name,
                    $trait->symbol
                );
                $this->writeln(wordwrap($trait->description, 100), "");
            }
        } else {
            $this->writeln('None', '');
        }

        $this->heading("MODIFIERS");
        if ($this->waypoint->modifiers) {
            foreach ($this->waypoint->modifiers as $i => $modifier) {
                $this->sprintf(
                    "<:GRN:>%d. %s (%s)",
                    $i + 1,
                    $modifier->name,
                    $modifier->symbol
                );
                $this->writeln(
                    wordwrap($trait->description ?? '', 80),
                    ""
                );
            }
        } else {
            $this->writeln("None");
        }
        $this->newline();

        return parent::output();
    }
}
