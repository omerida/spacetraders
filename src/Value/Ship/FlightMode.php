<?php

namespace Phparch\SpaceTraders\Value\Ship;

enum FlightMode: string
{
    case DRIFT = "DRIFT";
    case STEALTH = "STEALTH";
    case CRUISE = "CRUISE";
    case BURN = "BURN";

    public function getDefault(): self
    {
        return self::CRUISE;
    }
}
