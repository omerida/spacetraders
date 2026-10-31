<?php

namespace Phparch\SpaceTraders\Automation\Ship;

enum OrderType: string
{
    case REFUEL = 'REFUEL';
    case TRAVEL = 'TRAVEL';
    case EXTRACT_RESOURCES = 'EXTRACT_RESOURCES';
    case VISIT_MARKETPLACE = 'VISIT_MARKETPLACE';
    case SELL_GOODS = 'SELL_GOODS';
    case PURCHASE_GOODS = 'PURCHASE_GOODS';
    case PAUSE_UNTIL = 'PAUSE_UNTIL';
    case IDLE = 'IDLE';
}
