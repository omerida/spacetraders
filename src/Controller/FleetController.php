<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
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
    public function myAgent(): array
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
            throw new BadRequestException("Ship GET param missing");
        }

        return $id;
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
