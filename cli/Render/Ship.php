<?php

namespace Phparch\SpaceTradersCLI\Render;

use Phparch\SpaceTraders\Value;
use Phparch\SpaceTradersCLI\Render\Ship\Nav;
use Phparch\SpaceTradersCLI\Render\Ship\RegistrationInfo;

class Ship extends AbstractRenderer
{
    public function __construct(
        public Value\Ship $ship,
    ) {
    }

    public function output(): string
    {
        $this->heading($this->ship->symbol);
        $this->newline();

        $nav = new Nav($this->ship->nav);
        $this->passthru($nav);

        /* @todo render Crew Info */
        // FRAME
        $this->sprintf(
            '<:CYN:>## FRAME<:DEF:> %s (%s)',
            $this->ship->frame->name,
            $this->ship->frame->symbol->value,
        );
        $this->writeln($this->green(wordwrap($this->ship->frame->description, 80)));
        $this->sprintf(
            '<:YEL:>Condition<:DEF:>       %3s  <:YEL:>Integrity<:DEF:>     %3s  '
            . '<:YEL:>Module Slots<:DEF:> %s',
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
        $this->newline();
        // ENGINE
        $this->sprintf(
            '<:CYN:>## ENGINE<:DEF:> %s (%s)',
            $this->ship->engine->name,
            $this->ship->engine->symbol->value,
        );
        $this->writeln($this->green(wordwrap($this->ship->engine->description, 80)));
        $this->sprintf(
            '<:YEL:>Speed<:DEF:> %3s  <:YEL:>Condition<:DEF:> %s  <:YEL:>Integrity<:DEF:> %s',
            $this->ship->engine->speed,
            $this->ship->engine->condition,
            $this->ship->engine->integrity,
        );
        $this->sprintf(
            '<:MAG:>Power<:DEF:> %3s  <:MAG:>Crew<:DEF:> %3s  <:MAG:>Slots<:DEF:> %3s',
            $this->ship->engine->requirements->power,
            $this->ship->engine->requirements->crew,
            $this->ship->engine->requirements->slots ?? '-',
        );
        $this->newline();
        // REACTOR
        $this->sprintf(
            '<:CYN:>## REACTOR<:DEF:> %s (%s)',
            $this->ship->reactor->name,
            $this->ship->reactor->symbol->value,
        );
        $this->writeln($this->green(wordwrap($this->ship->reactor->description, 80)));

        $this->sprintf(
            '<:YEL:>Power Output<:DEF:> %3s  <:YEL:>Condition<:DEF:> %s  '
            . '<:YEL:>Integrity<:DEF:> %s',
            $this->ship->reactor->powerOutput,
            $this->ship->reactor->condition,
            $this->ship->reactor->integrity,
        );

        $this->sprintf(
            '<:MAG:>Power<:DEF:> %3s  <:MAG:>Crew<:DEF:> %3s  <:MAG:>Slots<:DEF:> %3s',
            $this->ship->reactor->requirements->power ?? '-',
            $this->ship->reactor->requirements->crew ?? '-',
            $this->ship->reactor->requirements->slots ?? '-',
        );
        $this->newline();
        $this->writeln($this->cyan('## MOUNTS'));

        if ($this->ship->mounts) {
            foreach ($this->ship->mounts as $i => $mount) {
                $this->sprintf(
                    '%d. %s <:YEL:>(%s)',
                    $i + 1,
                    $mount->name,
                    $mount->symbol->value,
                );
                $this->writeln($this->green('   ' . $mount->description));
                $this->sprintf(
                    '   <:YEL:>Strength<:DEF:> %d',
                    $mount->strength,
                );
                $this->sprintf(
                    '   <:MAG:>Power<:DEF:> %3s  <:MAG:>Crew<:DEF:> %3s',
                    $mount->requirements->power,
                    $mount->requirements->crew,
                );
            }
        } else {
            $this->writeln('None', '');
        }

        $this->newline();
        $reg = new RegistrationInfo($this->ship->registration);
        $this->passthru($reg);
        $this->divider(color: '<:BLU:>');

        return parent::output();
    }
}
