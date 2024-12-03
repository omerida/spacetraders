<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Value\WaypointSymbol;
use Phparch\SpaceTradersCLI\Command\HelpInfo;

#[HelpInfo(
    description: "Purchase a type of ship from a shipyard at a waypoint.",
    params: ['waypoint symbol', 'ship type']
)]
class PurchaseShipController extends CommandController
{
    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $args = $this->getArgs();
        $waypoint = $args[3] ?? null;
        if (!$waypoint) {
            throw new \InvalidArgumentException("Please specify waypoint as third parameter");
        }

        $waypoint = new WaypointSymbol($waypoint);
        $type = strtoupper($args[4]);
        if (!$type) {
            throw new \InvalidArgumentException("Please specify type as fourth parameter");
        }

        /* @TODO validate type is one of the allowed values */
        $response = $client->purchaseShip($waypoint, $type);

        var_dump($response);
    }
}
