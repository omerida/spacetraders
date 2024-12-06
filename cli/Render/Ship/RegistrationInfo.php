<?php

namespace Phparch\SpaceTradersCLI\Render\Ship;

use Phparch\SpaceTradersCLI\Render\AbstractRenderer;

class RegistrationInfo extends AbstractRenderer
{
    public function __construct(
        public \Phparch\SpaceTraders\Value\RegistrationInfo $info,
    )
    {
    }

    public function output(): string
    {
        $this->heading("REGISTRATION INFO");
        $this->sprintf(
            '<:BLU:>NAME: <:DEF:> %s / %s ',
            $this->info->name,
            $this->info->role->value,
        );

        $this->sprintf(
            '<:YEL:>FACTION: <:DEF:> %s',
            $this->info->factionSymbol
        );
        return parent::output();
    }
}
