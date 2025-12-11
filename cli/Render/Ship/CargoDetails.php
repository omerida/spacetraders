<?php

namespace Phparch\SpaceTradersCLI\Render\Ship;

use Phparch\SpaceTraders\Value\Ship;
use Phparch\SpaceTradersCLI\Render\AbstractRenderer;

class CargoDetails extends AbstractRenderer
{
    public function __construct(
        public Ship\CargoDetails $cargo,
    ) {
    }

    public function output(): string
    {
        $this->heading('CARGO');
        $this->sprintf(
            '<:BLU:>CAPACTITY: <:DEF:> %s of %s',
            $this->cargo->units,
            $this->cargo->capacity,
        );
        foreach ($this->cargo->inventory as $item) {
            $this->sprintf(
                '<:YEL:>%s: <:DEF:> %s units - %s, %s',
                $item->symbol->name,
                $item->units,
                $item->name,
                $item->description,
            );
        }
        $this->newline();
        return parent::output();
    }
}
