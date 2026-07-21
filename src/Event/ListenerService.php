<?php

namespace Phparch\SpaceTraders\Event;

use Phparch\SpaceTradersRest\Event\ContractAccepted;
use Crell\Tukio\Listener;

class ListenerService
{
    #[Listener]
    public function onContractAccepted(ContractAccepted $event): void {
        echo "event listener called.";
    }
}
