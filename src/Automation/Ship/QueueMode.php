<?php

namespace Phparch\SpaceTraders\Automation\Ship;

enum QueueMode: string
{
    case LOOP = 'LOOP';
    case ONCE = 'ONCE';
    case PAUSED = 'PAUSED';
}
