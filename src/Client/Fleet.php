<?php

namespace Phparch\SpaceTraders\Client;

use GuzzleHttp\Exception\ClientException;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Response;
use Phparch\SpaceTraders\Value\ScrapTransaction;
use Phparch\SpaceTraders\Value\Ship;
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
            Response\Fleet\ListShips::class
        );
    }

    public function dockShip(string $ship)
    {
        try {
            $response = $this->post(
                'my/ships/' . $ship . '/dock',
                data: [],
                authenticate: true
            );
            return $this->convertResponse(
                $response,
                Response\Fleet\DockShip::class
            );
        } catch (ClientException $e) {
            throw new \RuntimeException($e->getResponse()->getBody()->getContents(), 1);
        }
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
            Response\Fleet\ShipMounts::class
        );
    }

    public function getScrapShip(string $ship)
    {
        return $this->convertResponse(
            $this->get('my/ships/' . $ship . '/scrap'),
            ScrapTransaction::class
        );
    }

    public function navigateShip(string $ship, WaypointSymbol $waypointSymbol)
    {
        try {
            $response = $this->post(
                'my/ships/' . $ship . '/navigate',
                data: [
                    'waypointSymbol' => (string) $waypointSymbol,
                ],
                authenticate: true
            );
            return $this->convertResponse(
                $response,
                Response\Fleet\NavigateShip::class
            );
        } catch (ClientException $e) {
            throw new \RuntimeException($e->getResponse()->getBody()->getContents(), 1);
        }
    }

    public function orbitShip(string $ship)
    {
        try {
            $response = $this->post(
                'my/ships/' . $ship . '/orbit',
                data: [],
                authenticate: true
            );
            return $this->convertResponse(
                $response,
                Response\Fleet\OrbitShip::class
            );
        } catch (ClientException $e) {
            throw new \RuntimeException($e->getResponse()->getBody()->getContents(), 1);
        }
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
                $response,
                Response\Fleet\PurchaseShip::class
            );
        } catch (ClientException $e) {
            throw new \RuntimeException($e->getResponse()->getBody()->getContents(), 1);
        }
    }

    public function refuelShip(string $ship, ?int $units = null, bool $fromCargo = false)
    {
        try {
            $data['fromCargo'] = $fromCargo;
            if ($units > 0) {
                $data['units'] = $units;
            }
            $response = $this->post(
                'my/ships/' . $ship . '/refuel',
                data: $data,
                authenticate: true
            );
            return $this->convertResponse(
                $response,
                Response\Fleet\RefuelShip::class
            );
        } catch (ClientException $e) {
            throw new \RuntimeException($e->getResponse()->getBody()->getContents(), 1);
        }
    }
}
