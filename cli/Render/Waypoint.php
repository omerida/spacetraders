<?php

namespace Phparch\SpaceTradersCLI\Render;

use \Phparch\SpaceTraders\Response;
class Waypoint extends AbstractRenderer
{

    public function __construct(
        public Response\Systems\Waypoint $waypoint,
    ) {
    }

    public function output(): string
    {
        $this->sprintf(
            'SYSTEM SYMBOL: %s, %s (%d, %d)',
            $this->waypoint->symbol,
            $this->waypoint->type,
            $this->waypoint->x,
            $this->waypoint->y,
        );

        $this->sprintf(
            'CHART: Submitted by %s on %s',
            $this->waypoint->chart->submittedBy,
            $this->waypoint->chart->submittedOn->format(DATE_COOKIE),
        );

        $this->writeln("", "ORBITALS");
        if ($this->waypoint->orbitals) {
            foreach ($this->waypoint->orbitals as $orbital) {
                // TODO render an orbital
            }
        } else {
            $this->writeln("None");
        }

        $this->writeln("", "TRAITS");
        if ($this->waypoint->traits) {
            foreach ($this->waypoint->traits as $i => $trait) {
                $this->sprintf(
                    "%d. %s (%s)",
                    $i + 1,
                    $trait->name,
                    $trait->symbol
                );
                $this->writeln(wordwrap($trait->description, 70), "");
            }
        } else {
            $this->writeln("   None");
        }

        $this->writeln("MODIFIERS");
        if ($this->waypoint->modifiers) {
            foreach ($this->waypoint->modifiers as $i => $modifier) {
                $this->sprintf(
                    "%d. %s (%s)",
                    $i + 1,
                    $modifier->name,
                    $modifier->symbol
                );
                $this->writeln(
                    wordwrap($trait->description, 80),
                    ""
                );
            }
        } else {
            $this->writeln("None");
        }


        return parent::output();
    }
}