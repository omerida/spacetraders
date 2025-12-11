<?php

namespace Phparch\SpaceTraders\Value\Ship\Reactor;

enum Symbol: string
{
    case REACTOR_SOLAR_I = "REACTOR_SOLAR_I";
    case REACTOR_FUSION_I = "REACTOR_FUSION_I";
    case REACTOR_FISSION_I = "REACTOR_FISSION_I";
    case REACTOR_CHEMICAL_I = "REACTOR_CHEMICAL_I";
    case REACTOR_ANTIMATTER_I = "REACTOR_ANTIMATTER_I";
}
