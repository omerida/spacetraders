<?php

namespace Phparch\SpaceTraders\Event;

use Phparch\SpaceTradersRest\Event\ContractAccepted;
use Crell\Tukio\Listener;

/**
 * Any public method with the #[Listener] attribute is automatically
 * registered in the app's ServiceContainer.
 *
 * @see config/services.php
 */
class ListenerService
{
    public function __construct() {
    }

    public function onContractAccepted(ContractAccepted $event): void {
        echo "event listener called.";
    }
}
