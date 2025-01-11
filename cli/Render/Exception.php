<?php

namespace Phparch\SpaceTradersCLI\Render;

class Exception extends AbstractRenderer
{
    public function __construct(
        public \Exception $ex,
    )
    {
    }

    public function output(): string
    {
        $this->heading("ERROR");
        $this->sprintf(
            '<:RED:>%s<:DEF:> (%s)',
            $this->ex->getMessage(),
            $this->ex->getCode(),
        );
        $this->newline();

        return parent::output();
    }
}
