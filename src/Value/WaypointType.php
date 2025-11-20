<?php

namespace Phparch\SpaceTraders\Value;

enum WaypointType: string
{
    case ARTIFICIAL_GRAVITY_WELL = "ARTIFICIAL_GRAVITY_WELL";
    case ASTEROID = "ASTEROID";
    case ASTEROID_BASE = "ASTEROID_BASE";
    case ASTEROID_FIELD = "ASTEROID_FIELD";
    case DEBRIS_FIELD = "DEBRIS_FIELD";
    case ENGINEERED_ASTEROID = "ENGINEERED_ASTEROID";
    case FUEL_STATION = "FUEL_STATION";
    case GAS_GIANT = "GAS_GIANT";
    case GRAVITY_WELL = "GRAVITY_WELL";
    case JUMP_GATE = "JUMP_GATE";
    case MOON = "MOON";
    case NEBULA = "NEBULA";
    case ORBITAL_STATION = "ORBITAL_STATION";
    case PLANET = "PLANET";
}
