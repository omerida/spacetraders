<?php

namespace Phparch\SpaceTraders\Value\Ship;

enum CrewRotation: string
{
    case STRICT = "STRICT";
    case RELAXED = "RELAXED";

    public function getDefault(): self
    {
        return self::STRICT;
    }
}
