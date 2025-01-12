<?php

namespace Phparch\SpaceTraders\Client;

use GuzzleHttp\Exception\ClientException;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Response;
use Phparch\SpaceTraders\APIException;
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
        try {
            return $this->convertResponse(
                $this->get('my/ships'),
                Response\Fleet\ListShips::class
            );
        } catch (ClientException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
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
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }

    /**
     * @throws \RuntimeException
     */
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
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }

    public function getShip(string $ship): Ship
    {
        try {
            return $this->convertResponse(
                $this->get('my/ships/' . $ship),
                Ship::class
            );
        } catch (ClientException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }

    public function getShipCargo(string $ship): ShipCargoDetails
    {
        try {
            return $this->convertResponse(
                $this->get('my/ships/' . $ship . '/cargo'),
                ShipCargoDetails::class
            );
        } catch (ClientException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }

    public function getShipCooldown(string $ship): ShipCoolDown
    {
        try {
            return $this->convertResponse(
                $this->get('my/ships/' . $ship . '/cooldown'),
                ShipCoolDown::class
            );
        } catch (ClientException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }

    public function getShipMounts(string $ship): Response\Fleet\ShipMounts
    {
        try {
            return $this->convertResponse(
                $this->get('my/ships/' . $ship . '/mounts'),
                Response\Fleet\ShipMounts::class
            );
        } catch (ClientException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }

    public function getScrapShip(string $ship): ScrapTransaction
    {
        try {
            return $this->convertResponse(
                $this->get('my/ships/' . $ship . '/scrap'),
                ScrapTransaction::class
            );
        } catch (ClientException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
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
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
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
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
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
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
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
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }

    public function refuelShip(
        string $ship,
        ?int $units = null,
        bool $fromCargo = false
    ): Response\Fleet\RefuelShip
    {
        $data = [];
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
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }

    public function sellCargo(
        string $ship,
        string $cargo,
        int $units = null,
    ): Response\Fleet\SellCargo {
        $data = [];
        try {
            $data['symbol'] = $cargo;
            $data['units'] = $units;

            $response = $this->post(
                'my/ships/' . $ship . '/sell',
                data: $data,
                authenticate: true
            );
            return $this->convertResponse(
                $response,
                Response\Fleet\SellCargo::class
            );
        } catch (ClientException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }

    public function jettisonCargo(
        string $ship,
        string $cargo,
        int $units = null,
    ): Response\Fleet\JettisonCargo {
        $data = [];
        try {
            $data['symbol'] = $cargo;
            $data['units'] = $units;

            $response = $this->post(
                'my/ships/' . $ship . '/jettison',
                data: $data,
                authenticate: true
            );
            return $this->convertResponse(
                $response,
                Response\Fleet\JettisonCargo::class
            );
        } catch (ClientException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }
}
