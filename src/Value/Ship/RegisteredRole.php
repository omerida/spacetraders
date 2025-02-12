<?php

namespace Phparch\SpaceTraders\Value\Ship;

enum RegisteredRole: string
{
    case FABRICATOR = "FABRICATOR";
    case HARVESTER = "HARVESTER";
    case HAULER = "HAULER";
    case INTERCEPTOR = "INTERCEPTOR";
    case EXCAVATOR = "EXCAVATOR";
    case TRANSPORT = "TRANSPORT";
    case REPAIR = "REPAIR";
    case SURVEYOR = "SURVEYOR";
    case COMMAND = "COMMAND";
    case CARRIER = "CARRIER";
    case PATROL = "PATROL";
    case SATELLITE = "SATELLITE";
    case EXPLORER = "EXPLORER";
    case REFINERY = "REFINERY";
}
