<?php

namespace Phparch\SpaceTraders\Value\Goods;

enum TradeActivityLevel: string
{
    case WEAK = "WEAK";
    case GROWING = "GROWING";
    case STRONG = "STRONG";
    case RESTRICTED = "RESTRICTED";
}
