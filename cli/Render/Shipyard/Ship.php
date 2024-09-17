<?php

namespace Phparch\SpaceTradersCLI\Render\Shipyard;

use \Phparch\SpaceTraders\Value;
use \Phparch\SpaceTradersCLI\Render\AbstractRenderer;

class Ship extends AbstractRenderer {
        public function __construct(
        public Value\Shipyard\Ship $ship,
    ) {
    }

    public function output(): string
    {
        $this->sprintf(
            '<:YEL:>%s<:DEF:> (%s)',
            $this->ship->name,
            $this->ship->type
        );
        $this->writeln($this->green(wordwrap($this->ship->description, 80)));
        $this->newline();
        $this->sprintf(
            '<:YEL:>Supply<:DEF:> %s  <:YEL:>Activity<:DEF:> %s  <:YEL:>Price<:DEF:> %s',
            $this->ship->activity,
            $this->ship->supply,
            number_format($this->ship->purchasePrice),
        );
        $this->sprintf(
            '<:YEL:>Frame<:DEF:> %s (%s)',
            $this->ship->frame->name,
            $this->ship->frame->symbol,
        );
        $this->writeln($this->green(wordwrap($this->ship->frame->description, 80)));
        $this->sprintf(
            '<:YEL:>Condition<:DEF:>       %3s  <:YEL:>Integrity<:DEF:>     %3s  <:YEL:>Module Slots<:DEF:> %s',
            $this->ship->frame->condition,
            $this->ship->frame->integrity,
            $this->ship->frame->moduleSlots,
        );
        $this->sprintf(
            '<:YEL:>Mounting Points<:DEF:> %3s  <:YEL:>Fuel Capacity<:DEF:> %3s',
            $this->ship->frame->mountingPoints,
            $this->ship->frame->fuelCapacity,
        );
        $this->sprintf(
            '<:MAG:>Power<:DEF:> %3s  <:MAG:>Crew<:DEF:> %3s  <:MAG:>Slots<:DEF:> %3s',
            $this->ship->frame->requirements->power,
            $this->ship->frame->requirements->crew,
            $this->ship->frame->requirements->slots,
        );
        $this->writeln($this->blue('.......................'));
        return parent::output();
    }
}