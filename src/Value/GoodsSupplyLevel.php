<?php

namespace Phparch\SpaceTraders\Value;

enum GoodsSupplyLevel: string
{
    case SCARCE = "SCARCE";
    case LIMITED = "LIMITED";
    case MODERATE = "MODERATE";
    case HIGH = "HIGH";
    case ABUNDANT = "ABUNDANT";
}
