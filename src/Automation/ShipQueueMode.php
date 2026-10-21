<?php

namespace Phparch\SpaceTraders\Automation;

enum ShipQueueMode: string
{
    case LOOP = 'LOOP';
    case ONCE = 'ONCE';
    case PAUSED = 'PAUSED';
}
