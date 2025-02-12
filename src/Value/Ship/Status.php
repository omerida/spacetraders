<?php

namespace Phparch\SpaceTraders\Value\Ship;

enum Status: string
{
    case IN_TRANSIT = 'IN_TRANSIT';
    case IN_ORBIT = 'IN_ORBIT';
    case DOCKED = 'DOCKED';
}
