<?php

namespace Phparch\SpaceTraders\Client;

use GuzzleHttp\Exception\ClientException;
use http\Exception\RuntimeException;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Response\Fleet\PurchaseShip;
use Phparch\SpaceTraders\Value\ScrapTransaction;
use Phparch\SpaceTraders\Value\Ship;
use Phparch\SpaceTraders\Response\Fleet\ListShips;
use Phparch\SpaceTraders\Response\Fleet\ShipMounts;
use Phparch\SpaceTraders\Value\ShipCargoDetails;
use Phparch\SpaceTraders\Value\ShipCoolDown;
use Phparch\SpaceTraders\Value\WaypointSymbol;

/**
 * For endpoints under "fleet" group.
 * <https://spacetraders.stoplight.io/docs/spacetraders/>
 */
class Fleet extends Client
{
    public function ListShips()
    {
        return $this->convertResponse(
            $this->get('my/ships'),
            ListShips::class
        );
    }

    public function getShip(string $ship)
    {
        return $this->convertResponse(
            $this->get('my/ships/' . $ship),
            Ship::class
        );
    }

    public function getShipCargo(string $ship)
    {
        return $this->convertResponse(
            $this->get('my/ships/' . $ship . '/cargo'),
            ShipCargoDetails::class
        );
    }

    public function getShipCooldown(string $ship)
    {
        return $this->convertResponse(
            $this->get('my/ships/' . $ship . '/cooldown'),
            ShipCoolDown::class
        );
    }

    public function getShipMounts(string $ship)
    {
        return $this->convertResponse(
            $this->get('my/ships/' . $ship . '/mounts'),
            ShipMounts::class
        );
    }

    public function getScrapShip(string $ship)
    {
        return $this->convertResponse(
            $this->get('my/ships/' . $ship . '/scrap'),
            ScrapTransaction::class
        );
    }

    public function purchaseShip(WaypointSymbol $waypoint, string $type)
    {
        try {
            $response = $this->post(
                'my/ships',
                data: [
                    'waypointSymbol' => (string) $waypoint,
                    'shipType' => $type,
                ],
                authenticate: true
            );
            return $this->convertResponse(
                $response, PurchaseShip::class
            );
        } catch (ClientException $e) {
            throw new \RuntimeException($e->getResponse()->getBody()->getContents(), true);
        }


    }
}

