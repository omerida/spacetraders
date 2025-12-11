<?php

namespace Phparch\SpaceTraders\Controller;

use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Interface\TwigAware;
use Phparch\SpaceTraders\Value\Goods\Symbol;
use Phparch\SpaceTraders\Value\Waypoint\Type;
use Psr\Http\Message\ResponseInterface;

class IndexController implements TwigAware
{
    use Trait\TwigAwareController;

    public function __construct(
        private Client\Agents $client,
        private Client\Fleet $fleet,
    ) {
    }

    #[Route(
        name: 'homepage',
        path: '/',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function index(): ResponseInterface
    {
        $agent = $this->client->myAgent();

        $systems = [];

        $systems[$agent->headquarters->system] = [
            'name' => $agent->headquarters->system,
            'value' => $agent->headquarters->system,
        ];

        $ships = $this->fleet->listShips();
        foreach ($ships->ships as $ship) {
            $systems[$ship->nav->systemSymbol->system] = [
                'name' => $ship->nav->systemSymbol->system,
                'value' => $ship->nav->systemSymbol->system
            ];
        }

        $shipOpts = [];
        foreach ($ships->ships as $ship) {
            $shipOpts[] = [
                'name' => $ship->symbol,
                'value' => $ship->symbol
            ];
        }
        return $this->render('index.html.twig', [
            'headTitle' => 'Space Traders Client',
            'agent' => $agent,
            'ships' => $ships->ships,
            'shipOpts' => $shipOpts,
            'systems' => $systems,
            'types' => Type::cases(),
            'cargoTypes' => Symbol::cases(),
        ]);
    }
}
