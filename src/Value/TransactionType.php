<?php

namespace Phparch\SpaceTraders\Value;

enum TransactionType: string
{
    case PURCHASE = "PURCHASE";
    case SELL = "SELL";
}
