<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Response\Fleet\DockShip;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(
    description: "Dock ship at a waypoing.",
    params: ['ship symbol']
)]
class DockShipController extends CommandController
{
    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $args = $this->getArgs();
        $shipSymbol = $args[3] ?? null;
        if (!$shipSymbol) {
            throw new \InvalidArgumentException("Please specify ship symbol as third parameter");
        }

        $response = $client->dockShip($shipSymbol);

        $nav = new Render\Ship\Nav($response->nav);
        echo $nav->output();
    }

    private function getMock() // @phpstan-ignore-line
    {
        /* @phpcs:ignore */
        $json = '{"data":{"nav":{"systemSymbol":"X1-J69","waypointSymbol":"X1-J69-AD5D","route":{"origin":{"symbol":"X1-J69-H56","type":"MOON","systemSymbol":"X1-J69","x":35,"y":-30},"destination":{"symbol":"X1-J69-AD5D","type":"ENGINEERED_ASTEROID","systemSymbol":"X1-J69","x":24,"y":-13},"arrival":"2024-12-04T04:14:24.671Z","departureTime":"2024-12-04T04:11:22.671Z"},"status":"DOCKED","flightMode":"CRUISE"}}}';
        $response = json_decode($json, true);

        return DockShip::fromArray($response['data']);
    }
}
