<?php

namespace Phparch\SpaceTraders\Value;

enum ContractType: string
{
    case PROCUREMENT = "PROCUREMENT";
    case TRANSPORT = "TRANSPORT";
    case SHUTTLE = "SHUTTLE";
}
