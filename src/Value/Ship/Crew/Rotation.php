<?php

namespace Phparch\SpaceTraders\Value\Ship\Crew;

enum Rotation: string
{
    case STRICT = "STRICT";
    case RELAXED = "RELAXED";

    public function getDefault(): self
    {
        return self::STRICT;
    }
}
