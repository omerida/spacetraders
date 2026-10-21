<?php

namespace Phparch\SpaceTraders\Automation;

enum ShipOrderType: string
{
    case TRAVEL = 'TRAVEL';
    case EXTRACT_RESOURCES = 'EXTRACT_GOODS';
    case REFUEL = 'REFUEL';
    case SELL_GOODS = 'SELL_GOODS';
    case PURCHASE_GOODS = 'PURCHASE_GOODS';
    case VISIT_MARKETPLACE = 'VISIT_MARKETPLACE';
    case PAUSE_UNTIL = 'PAUSE_UNTIL';
    case IDLE = 'IDLE';
}
