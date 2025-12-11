<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use InvalidArgumentException;
use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Value\Fleet\OrbitShip;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(
    description: "Move your ship into orbit at its current location.",
    params: ['ship symbol']
)]
class OrbitShipController extends CommandController
{
    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $args = $this->getArgs();
        $shipSymbol = $args[3] ?? null;
        if (!$shipSymbol) {
            throw new InvalidArgumentException("Please specify ship symbol as third parameter");
        }

        $response = $client->orbitShip($shipSymbol);
        $nav = new Render\Ship\Nav($response->nav);
        echo $nav->output();
    }

    private function getSavedResponse() // @phpstan-ignore-line
    {
        // phpcs:ignore
        $json = '{"data":{"nav":{"systemSymbol":"X1-J69","waypointSymbol":"X1-J69-H56","route":{"origin":{"symbol":"X1-J69-H56","type":"MOON","systemSymbol":"X1-J69","x":35,"y":-30},"destination":{"symbol":"X1-J69-H56","type":"MOON","systemSymbol":"X1-J69","x":35,"y":-30},"arrival":"2024-12-03T04:59:47.387Z","departureTime":"2024-12-03T04:59:47.387Z"},"status":"IN_ORBIT","flightMode":"CRUISE"}}}';
        $response = json_decode($json, true);

        return OrbitShip::fromArray($response['data']);
    }
}
