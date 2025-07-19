<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\Controller\Trait\TwigAwareController;
use Phparch\SpaceTraders\RequestAwareInterface;
use Phparch\SpaceTraders\TwigAwareInterface;
use Phparch\SpaceTraders\Value\WaypointSymbol;
use Psr\Http\Message\ResponseInterface;

class MarketController implements RequestAwareInterface, TwigAwareInterface
{
    use RequestAwareController;
    use TwigAwareController;

    public function __construct(
        private Client\Systems $client,
    ) {
    }

    /**
     * @throws BadRequestException
     */
    #[Route(
        name: 'systems_market',
        path: '/systems/market',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function systemsWaypoints(): ResponseInterface
    {
        $query = $this->getRequest()->getQueryParams();
        $id = $query['id'] ?? null;

        if (!$id || !is_string($id)) {
            throw new BadRequestException("Waypoint ID GET param missing");
        }

        $id = strtoupper($id);
        if (!preg_match('/[A-Z0-9\-]+/', $id)) {
            throw new BadRequestException("Invalid characters in waypoint ID");
        }

        $point = new WaypointSymbol($id);

        $market = $this->client->market(
            system: $point->system,
            waypoint: $point->waypoint
        );

        return $this->render('systems/market.html.twig', [
            'headTitle' => 'Market ' . $market->symbol,
            'symbol' => $market->symbol,
            'exports' => $market->exports,
            'imports' => $market->imports,
            'exchange' => $market->exchange,
            'transactions' => $market->transactions,
            'tradeGoods' => $market->tradeGoods,
        ]);
    }
}
