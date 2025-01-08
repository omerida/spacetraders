<?php

namespace Phparch\SpaceTradersCLI\Render\Ship;

use Phparch\SpaceTradersCLI\Render\AbstractRenderer;
use Phparch\SpaceTraders\Value\Ship;

class ExtractionDetails extends AbstractRenderer
{
    public function __construct(
        public Ship\ExtractionDetails $details
    ) {
    }

    public function output(): string
    {
        $this->heading('EXTRACTION DETAILS');
        $this->sprintf(
            '<:BLU:>SHIP: <:DEF:> %s',
            $this->details->shipSymbol
        );
        $this->sprintf(
            '<:BLU:>YIELD: <:DEF:> %s units of <:YEL:>%s',
            $this->details->yield->units,
            $this->details->yield->symbol->name,
        );
        $this->newline();
        return parent::output();
    }
}
