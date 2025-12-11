<?php

namespace Phparch\SpaceTraders\Value\Contract;

enum Type: string
{
    case PROCUREMENT = "PROCUREMENT";
    case TRANSPORT = "TRANSPORT";
    case SHUTTLE = "SHUTTLE";
}
