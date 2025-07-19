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
use Phparch\SpaceTraders\Value\WaypointType;
use Psr\Http\Message\ResponseInterface;

class WaypointController implements RequestAwareInterface, TwigAwareInterface
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
        name: 'systems_waypoint',
        path: '/systems/waypoint',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function systemsWaypoint(): ResponseInterface
    {
        $point = $this->getWaypoint();

        $location = $this->client->systemLocation(
            system: $point->system,
            waypoint: $point->waypoint
        );

        return $this->render('systems/waypoint.html.twig', [
            'headTitle' => 'Viewpoint Details - ' . $point,
            'location' => $location
        ]);
    }

    /**
     * @return array<mixed>
     * @throws BadRequestException
     */
    #[Route(name: 'search_waypoints', path: '/systems/waypoint/search', methods: ['GET'])]
    public function systemsWaypointSearch(): array
    {
        /**
         * @var array{
         *     system ?: ?string,
         *     traits ?: ?string,
         *     type ?: null|value-of<WaypointType>
         * } $query
         */
        $query = $this->getRequest()->getQueryParams();
        $system = $query['system'] ?? '';

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

        $query['traits'] = trim($query['traits'] ?? '');
        if (empty($query['traits'])) {
            unset($query['traits']);
        } else {
            $query['traits'] = strtoupper($query['traits']);
        }

        if (empty($query['type'])) {
            unset($query['type']);
        } else {
            $query['type'] = strtoupper($query['type']);
            // Validate the type is allowed
            if (!WaypointType::tryFrom($query['type'])) {
                throw new BadRequestException("Unknown waypoint type.");
            }
        }

        return (array) $this->client->waypoints($system, $query);
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'view_shipyard', path: '/systems/waypoint/shipyard', methods: ['GET'])]
    public function viewShipyard(): array
    {
        $point = $this->getWaypoint();

        return (array) $this->client->shipyard($point->system, $point->waypoint);
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'view_market', path: '/systems/waypoint/market', methods: ['GET'])]
    public function viewMarket(): array
    {
        $point = $this->getWaypoint();

        return (array) $this->client->market($point->system, $point->waypoint);
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
