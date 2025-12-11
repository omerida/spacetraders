<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use InvalidArgumentException;
use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Value\Fleet\NavigateShip;
use Phparch\SpaceTraders\Value\Waypoint;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(
    description: "Send a ship to a waypoint.",
    params: ['ship symbol', 'waypoint symbol']
)]
class NavigateShipController extends CommandController
{
    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $args = $this->getArgs();
        $shipSymbol = $args[3] ?? null;
        if (!$shipSymbol) {
            throw new InvalidArgumentException("Please specify ship symbol as third parameter");
        }

        $waypoint = $args[4] ?? null;
        if (!$waypoint) {
            throw new InvalidArgumentException("Please specify waypoint as third parameter");
        }
        $waypoint = new Waypoint\Symbol($waypoint);

        $response = $client->navigateShip($shipSymbol, $waypoint);

        $fuel = new Render\Ship\Fuel($response->fuel);
        echo $fuel->output();

        $nav = new Render\Ship\Nav($response->nav);
        echo $nav->output();
    }

    private function getMock() // @phpstan-ignore-line
    {
        /* @phpcs:ignore */
        $json = '{"data":{"nav":{"systemSymbol":"X1-J69","waypointSymbol":"X1-J69-AD5D","route":{"origin":{"symbol":"X1-J69-H56","type":"MOON","systemSymbol":"X1-J69","x":35,"y":-30},"destination":{"symbol":"X1-J69-AD5D","type":"ENGINEERED_ASTEROID","systemSymbol":"X1-J69","x":24,"y":-13},"arrival":"2024-12-04T04:14:24.671Z","departureTime":"2024-12-04T04:11:22.671Z"},"status":"IN_TRANSIT","flightMode":"CRUISE"},"fuel":{"current":60,"capacity":80,"consumed":{"amount":20,"timestamp":"2024-12-04T04:11:22.693Z"}},"events":[]}}';
        $response = json_decode($json, true);

        return NavigateShip::fromArray($response['data']);
    }
}
