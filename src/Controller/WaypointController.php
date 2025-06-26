<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Value\WaypointSymbol;
use Phparch\SpaceTraders\Value\WaypointType;

class WaypointController extends RequestAwareController
{
    public function __construct(
        private Client\Systems $client,
    ) {
    }

    /**
     * @return array<mixed>
     * @throws BadRequestException
     */
    #[Route(name: 'systems_waypoint', path: '/systems/waypoint', methods: ['GET'])]
    public function systemsWaypoints(): array
    {
        $point = $this->getWaypoint();

        return (array) $this->client->systemLocation(
            system: $point->system,
            waypoint: $point->waypoint
        );
    }

    /**
     * @return array<mixed>
     * @throws BadRequestException
     */
    #[Route(name: 'search_waypoints', path: '/systems/waypoint/search', methods: ['GET'])]
    public function systemsWaypointSearch(): array
    {
        $query = $this->getRequest()->getQueryParams();
        $system = $query['system'] ?? null;

        if (!$system || !is_string($system)) {
            throw new BadRequestException("Please specify the system");
        }

        unset($query['system']);

        // Whitelist of allowed query params that we pass on to the API
        $allowed = ['traits', 'type'];
        $unknown = array_diff(array_keys($query), $allowed);
        if ($unknown) {
            throw new BadRequestException("Unknown query: " . implode('&', $unknown));
        }

        if ($query['type'] && is_string($query['type'])) {
            $query['type'] = strtoupper($query['type']);
            // Validate the type is allowed
            if (!WaypointType::tryFrom($query['type'])) {
                throw new BadRequestException("Unknown waypoint type.");
            }
        }

        return (array) $this->client->waypoints($system, $query);
    }

    private function getWaypoint(): WaypointSymbol
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

        return new WaypointSymbol($id);
    }
}
