<?php

namespace Phparch\SpaceTraders\Controller;

use GuzzleHttp\Psr7\Response;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Response\Agent;
use Phparch\SpaceTraders\Response\Fleet\ListShips;
use Phparch\SpaceTraders\Value\GoodsSymbol;
use Phparch\SpaceTraders\Value\Ship\FlightMode;
use Phparch\SpaceTraders\Value\WaypointType;
use Psr\Http\Message\ResponseInterface;

class IndexController extends Abstract\TwigAwareController
{
    public function __construct(
        private Client\Agents $client,
        private Client\Fleet $fleet,
    ) {
    }

    #[Route(name: 'homepage', path: '/', methods: ['GET'], strategy: 'application')]
    public function index(): ResponseInterface
    {
        $ships = $this->fleet->listShips();

        $shipOpts = [];
        foreach ($ships->ships as $ship) {
            $shipOpts[] = [
                'name' => $ship->symbol,
                'value' => $ship->symbol
            ];
        }
        return $this->render('index.html.twig', [
            'headTitle' => 'Space Traders Client',
            'agent' => $this->client->myAgent(),
            'ships' => $ships->ships,
            'shipOpts' => $shipOpts,
            'types' => WaypointType::cases(),
            'flightModes' => FlightMode::cases(),
            'cargoTypes' => GoodsSymbol::cases(),
        ]);
    }

    private function hello(Agent $agent, ListShips $ships): void
    {
        $ship_rows = "";
        $ship_opts = "";
        foreach ($ships->ships as $ship) {
            $ship_rows .= "<tr><td>{$ship->symbol}</td>";
            $ship_rows .= "<td>{$ship->nav->waypointSymbol}</td>";
            $ship_rows .= "<td>{$ship->nav->status->value}</td>";
            $ship_rows .= "<td>{$ship->nav->flightMode->value}</td>";
            $ship_rows .= "<td>{$ship->fuel->current} / {$ship->fuel->capacity}</td>";
            $ship_rows .= "<td>{$ship->cooldown->remainingSeconds}</td>";
            $ship_rows .= "<td><a href=\"/ship/info?ship={$ship->symbol}\" "
            . "target='_blank'>[view]</a></td>";
            $ship_rows .= "<td>{$ship->cargo->units} / {$ship->cargo->capacity} "
            . "<a href=\"/ship/cargo?ship={$ship->symbol}\" target='_blank'>[cargo]</a></td>";
            $ship_rows .= "</tr>";

            $ship_opts .= "<option value='{$ship->symbol}'>{$ship->symbol}</option>";
        }
    }
}
