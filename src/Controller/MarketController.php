<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Value\WaypointSymbol;

class MarketController extends RequestAwareController
{
    public function __construct(
        private Client\Systems $client,
    )
    {
    }

    /**
     * @return array<mixed>
     * @throws BadRequestException
     */
    #[Route(name: 'systems_market', path: '/systems/market', methods: ['GET'])]
    public function systemsWaypoints(): array
    {
        $query = $this->getRequest()->getQueryParams();
        $id = $query['id'] ?? null;

        if (!$id || !is_string($id)) {
            throw new BadRequestException("Waypoint ID GET param missing");
        }

        $id = strtoupper($id);
        if (!preg_match('/[A-Z0-9\-]+/', $id)) {
            throw new BadRequestException("Invalid characters in waypoing ID");
        }

        $point = new WaypointSymbol($id);

        return (array) $this->client->market(
            system: $point->system,
            waypoint: $point->waypoint
        );
    }
}
