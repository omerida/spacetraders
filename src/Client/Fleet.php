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
    public function ListShips(): Response\Fleet\ListShips
    {
        return $this->convertResponse(
            $this->get('my/ships'),
            Response\Fleet\ListShips::class
        );
    }

    public function dockShip(string $ship): Response\Fleet\DockShip
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

    public function extractShip(string $ship): Response\Fleet\ExtractResources
    {
        try {
            $response = $this->post(
                'my/ships/' . $ship . '/extract',
                data: [],
                authenticate: true
            );
            return $this->convertResponse(
                $response,
                Response\Fleet\ExtractResources::class
            );
        } catch (ClientException $e) {
            throw new \RuntimeException($e->getResponse()->getBody()->getContents(), 1);
        }
    }

    public function getShip(string $ship): Ship
    {
        return $this->convertResponse(
            $this->get('my/ships/' . $ship),
            Ship::class
        );
    }

    public function getShipCargo(string $ship): ShipCargoDetails
    {
        return $this->convertResponse(
            $this->get('my/ships/' . $ship . '/cargo'),
            ShipCargoDetails::class
        );
    }

    public function getShipCooldown(string $ship): ShipCoolDown
    {
        return $this->convertResponse(
            $this->get('my/ships/' . $ship . '/cooldown'),
            ShipCoolDown::class
        );
    }

    public function getShipMounts(string $ship): Response\Fleet\ShipMounts
    {
        return $this->convertResponse(
            $this->get('my/ships/' . $ship . '/mounts'),
            Response\Fleet\ShipMounts::class
        );
    }

    public function getScrapShip(string $ship): ScrapTransaction
    {
        return $this->convertResponse(
            $this->get('my/ships/' . $ship . '/scrap'),
            ScrapTransaction::class
        );
    }

    public function navigateShip(
        string $ship,
        WaypointSymbol $waypointSymbol
    ): Response\Fleet\NavigateShip
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

    public function orbitShip(string $ship): Response\Fleet\OrbitShip
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

    public function setNavMode(string $ship, string $flightMode): Ship\Nav
    {
        try {
            $response = $this->patch(
                sprintf('my/ships/%s/nav', $ship),
                data: [
                    'flightMode' => (string) $flightMode,
                ],
                authenticate: true
            );
            return $this->convertResponse(
                $response,
                Ship\Nav::class
            );
        } catch (ClientException $e) {
            throw new \RuntimeException($e->getResponse()->getBody()->getContents(), 1);
        }
    }

    public function purchaseShip(
        WaypointSymbol $waypoint,
        string $type
    ): Response\Fleet\PurchaseShip
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

    public function refuelShip(
        string $ship,
        ?int $units = null,
        bool $fromCargo = false
    ): Response\Fleet\RefuelShip
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
