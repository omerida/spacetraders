<?php

namespace Phparch\SpaceTraders\Trait;

trait TerminalOutputHelper
{
    public function outputVar(mixed $value): void
    {
        $this->out(print_r($value, true));
    }
}
