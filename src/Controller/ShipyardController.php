<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\RequestAwareInterface;
use Phparch\SpaceTraders\Value\WaypointSymbol;

class ShipyardController implements RequestAwareInterface
{
    use RequestAwareController;

    public function __construct(
        private Client\Systems $client,
    )
    {
    }

    /**
     * @return array<mixed>
     * @throws BadRequestException
     */
    #[Route(name: 'systems_shipyard', path: '/systems/shipyard', methods: ['GET'])]
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

        return (array) $this->client->shipyard(
            system: $point->system,
            waypoint: $point->waypoint
        );
    }
}
