<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Value\Ship\FlightMode;
use Phparch\SpaceTraders\Value\WaypointSymbol;

class FleetController extends RequestAwareController
{
    public function __construct(
        private Client\Fleet $client,
    ) {
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'my_ships', path: '/my/ships', methods: ['GET'])]
    public function myShips(): array
    {
        return (array) $this->client->listShips();
    }

    /**
     * @return array<mixed>
     * @throws BadRequestException
     */
    #[Route(name: 'fleet_list_ships', path: '/fleet/ships', methods: ['GET'])]
    public function listShips(): array
    {
        return (array) $this->client->listShips();
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'dock_ship', path: '/ship/dock', methods: ['POST'])]
    public function dockShip(): array
    {
        $ship = $this->getShipIdFromPost();
        return (array) $this->client->dockShip($ship);
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'set_ship_nav_mode', path: '/ship/set-flight-mode', methods: ['POST'])]
    public function setShipNavMode(): array
    {
        $ship = $this->getShipIdFromPost();
        /**
         * @var array{order: string} $post
         */
        $post = (array) $this->getRequest()->getParsedBody();

        $flightMode = strtoupper($post['flightmode'] ?? '');
        if (!$flightMode || !is_string($flightMode)) {
            throw new BadRequestException("Please specify the flight mode");
        }

        if (!FlightMode::tryFrom($flightMode)) {
            throw new BadRequestException("Unknown flight mode.");
        }
        return (array) $this->client->setNavMode($ship, $flightMode);
    }

    /**
     * @return array<mixed>
     * @throws BadRequestException
     */
    #[Route(name: 'order_ship', path: '/ship/orders', methods: ['POST'])]
    public function orderShip(): array
    {
        $ship = $this->getShipIdFromPost();

        /**
         * @var array{order: string} $post
         */
        $post = (array) $this->getRequest()->getParsedBody();

        $order = strtolower($post['order'] ?? '');

        switch ($order) {
            case 'dock':
                return (array) $this->client->dockShip($ship);
            case 'orbit':
                return (array) $this->client->orbitShip($ship);
            case 'extract':
                return (array) $this->client->extractShip($ship);
            case 'refuel':
                return (array) $this->client->refuelShip($ship);
            default:
                throw new BadRequestException("Unknown or missing order");
        }
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'refuel_ship', path: '/ship/refuel', methods: ['POST'])]
    public function refuelShip(): array
    {
        $ship = $this->getShipIdFromPost();
        return (array) $this->client->refuelShip($ship);
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'orbit_ship', path: '/ship/orbit', methods: ['POST'])]
    public function orbitShip(): array
    {
        $ship = $this->getShipIdFromPost();
        return (array) $this->client->orbitShip($ship);
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'extract_ship', path: '/ship/extract', methods: ['POST'])]
    public function extractShip(): array
    {
        $ship = $this->getShipIdFromPost();
        return (array) $this->client->extractShip($ship);
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'ship_info', path: '/ship/info', methods: ['GET'])]
    public function shipInfo(): array
    {
        $ship = $this->getShipID();
        return (array) $this->client->getShip($ship);
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'navigate_ship', path: '/ship/navigate', methods: ['POST'])]
    public function navigateShip(): array
    {
        $ship = $this->getShipIdFromPost();
        $waypoint = $this->getWaypointFromPost();
        return (array) $this->client->navigateShip($ship, $waypoint);
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'ship_cargo', path: '/ship/cargo', methods: ['GET'])]
    public function shipCargo(): array
    {
        $ship = $this->getShipID();
        return (array) $this->client->getShipCargo($ship);
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'ship_cooldown', path: '/ship/cooldown', methods: ['GET'])]
    public function shipCooldown(): array
    {
        $ship = $this->getShipID();
        return (array) $this->client->getShipCooldown($ship);
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'ship_mounts', path: '/ship/mounts', methods: ['GET'])]
    public function shipMounts(): array
    {
        $ship = $this->getShipID();
        return (array) $this->client->getShipMounts($ship);
    }

    private function getShipIdFromPost(): string
    {
        $post = (array) $this->getRequest()->getParsedBody();
        $id = $post['ship'] ?? null;

        if (!$id || !is_string($id)) {
            throw new BadRequestException("Ship POST param missing");
        }

        return $id;
    }

    private function getWaypointFromPost(): WaypointSymbol
    {
        $post = (array) $this->getRequest()->getParsedBody();
        $waypoint = $post['waypoint'] ?? null;

        if (!$waypoint || !is_string($waypoint)) {
            throw new BadRequestException("Waypoin param missing");
        }

        return new WaypointSymbol($waypoint);
    }

    private function getShipID(): string
    {
        $query = $this->getRequest()->getQueryParams();
        $id = $query['ship'] ?? null;

        if (!$id || !is_string($id)) {
            throw new BadRequestException("Ship GET param missing");
        }

        return $id;
    }
}
