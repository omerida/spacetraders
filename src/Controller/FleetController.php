<?php

namespace Phparch\SpaceTraders\Controller;

use GuzzleHttp\Exception\GuzzleException;
use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Interface;
use Phparch\SpaceTradersRest\APIException;
use Phparch\SpaceTradersRest\Client;
use Phparch\SpaceTradersRest\Exception\APIAuthentication;
use Phparch\SpaceTradersRest\Exception\APIFailure;

class FleetController implements Interface\RequestAware, Interface\TwigAware
{
    use Trait\RequestAwareController;
    use Trait\TwigAwareController;

    public function __construct(
        private Client\Fleet $fleet,
    ) {
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'my_ships', path: '/my/ships', methods: ['GET'])]
    public function myShips(): array
    {
        return (array) $this->fleet->listShips();
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'fleet_list_ships', path: '/fleet/ships', methods: ['GET'])]
    public function listShips(): array
    {
        return (array) $this->fleet->listShips();
    }

    /**
     * @return array<mixed>
     * @throws APIAuthentication
     * @throws APIFailure
     * @throws BadRequestException
     * @throws GuzzleException
     * @throws \JsonException
     */
    #[Route(
        name: 'ship_mounts',
        path: '/ship/mounts',
        methods: ['GET']
    )]
    public function shipMounts(): array
    {
        $ship = $this->getShipID();
        return (array) $this->fleet->getShipMounts($ship);
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
