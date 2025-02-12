<?php

namespace Phparch\SpaceTraders\Value;

enum GoodsActivityLevel: string
{
    case WEAK = "WEAK";
    case GROWING = "GROWING";
    case STRONG = "STRONG";
    case RESTRICTED = "RESTRICTED";
}
