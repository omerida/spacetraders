<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\Controller\Trait\TwigAwareController;
use Phparch\SpaceTraders\RequestAwareInterface;
use Phparch\SpaceTraders\TwigAwareInterface;
use Phparch\SpaceTraders\Value\GoodsSymbol;
use Phparch\SpaceTraders\Value\Ship\FlightMode;
use Phparch\SpaceTraders\Value\WaypointSymbol;
use Psr\Http\Message\ResponseInterface;

class FleetController implements RequestAwareInterface, TwigAwareInterface
{
    use RequestAwareController;
    use TwigAwareController;

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

    #[Route(
        name: 'set_ship_nav_mode',
        path: '/ship/set-flight-mode',
        methods: ['POST'],
        strategy: 'application'
    )]
    public function setShipNavMode(): ResponseInterface
    {
        $ship = $this->getShipIdFromPost();
        /**
         * @var array{order: string} $post
         */
        $post = (array) $this->getRequest()->getParsedBody();

        $flightMode = strtoupper($post['flightmode'] ?? '');
        if (!$flightMode) {
            throw new BadRequestException("Please specify the flight mode");
        }

        if (!FlightMode::tryFrom($flightMode)) {
            throw new BadRequestException("Unknown flight mode.");
        }

        $response = $this->client->setNavMode($ship, $flightMode);
        return $this->render('ships/set-nav-mode.html.twig', [
            'nav' => $response->nav,
            'fuel' => $response->fuel,
        ]);
    }

    /**
     * @throws BadRequestException
     */
    #[Route(
        name: 'order_ship',
        path: '/ship/orders',
        methods: ['POST'],
        strategy: 'application'
    )]
    public function orderShip(): ResponseInterface
    {
        $ship = $this->getShipIdFromPost();

        /**
         * @var array{order: string} $post
         */
        $post = (array) $this->getRequest()->getParsedBody();

        $order = strtolower($post['order'] ?? '');

        switch ($order) {
            case 'dock':
                $response = $this->client->dockShip($ship);
                return $this->render('partials/ship-nav-table.html.twig', [
                    'nav' => $response->nav,
                ]);

            case 'orbit':
                $response = $this->client->orbitShip($ship);
                return $this->render('partials/ship-nav-table.html.twig', [
                    'nav' => $response->nav,
                ]);

            case 'extract':
                $response = $this->client->extractShip($ship);
                return $this->render('ships/ship-extract.html.twig', [
                    'cooldown' => $response->cooldown,
                    'cargo' => $response->cargo,
                    'extraction' => $response->extraction,
                ]);
            case 'refuel':
                $response = $this->client->refuelShip($ship);
                return $this->render('ships/ship-refuel.html.twig', [
                    'agent' => $response->agent,
                    'fuel' => $response->fuel,
                    'transaction' => $response->transaction
                ]);
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
     * @throws BadRequestException
     */
    #[Route(name: 'sell_goods', path: '/ship/sell-goods', methods: ['POST'])]
    public function sellGoods(): array
    {
        $ship = $this->getShipIdFromPost();

        /**
         * @var array{good: string, units: int} $post
         */
        $post = (array) $this->getRequest()->getParsedBody();

        $good = strtoupper($post['good'] ?? '');
        if (!$good) {
            throw new BadRequestException("Please specify good to sell");
        }

        if (!GoodsSymbol::tryFrom($good)) {
            throw new BadRequestException("Unknown good to sell.");
        }

        $units = $post['units'] ?? 0;

        return (array) $this->client->sellCargo($ship, $good, $units);
    }

    /**
     * @return array<mixed>
     * @throws BadRequestException
     */
    #[Route(name: 'jettison_goods', path: '/ship/jettison-goods', methods: ['POST'])]
    public function jettisonCargo(): array
    {
        $ship = $this->getShipIdFromPost();

        /**
         * @var array{good?: string, units?: int} $post
         */
        $post = (array) $this->getRequest()->getParsedBody();

        $good = strtoupper($post['good'] ?? '');
        if (!$good) {
            throw new BadRequestException("Please specify good to sell");
        }

        if (!GoodsSymbol::tryFrom($good)) {
            throw new BadRequestException("Unknown good to sell.");
        }

        $units = $post['units'] ?? 0;

        return (array) $this->client->jettisonCargo($ship, $good, $units);
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

    #[Route(
        name: 'ship_info',
        path: '/ship/info',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function shipInfo(): ResponseInterface
    {
        $ID = $this->getShipID();
        $ship = $this->client->getShip($ID);

        $atFuelStation = (
            $ship->nav->route->destination->isFuelStation()
            && !$ship->nav->isInTransit()
        );

        return $this->render('ships/info.html.twig', [
            'ship' => $ship,
            'flightModes' => FlightMode::cases(),
            'atFuelStation' => $atFuelStation,
        ]);
    }

    #[Route(
        name: 'navigate_ship',
        path: '/ship/navigate',
        methods: ['POST'],
        strategy: 'application'
    )]
    public function navigateShip(): ResponseInterface
    {
        $ship = $this->getShipIdFromPost();
        $waypoint = $this->getWaypointFromPost();
        $response =  $this->client->navigateShip($ship, $waypoint);

        return $this->render('ships/navigate-ship.html.twig', [
            'nav' => $response->nav,
            'fuel' => $response->fuel,
            'events' => $response->events,
        ]);
    }

    #[Route(
        name: 'ship_cargo',
        path: '/ship/cargo',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function shipCargo(): ResponseInterface
    {
        $ship = $this->getShipID();

        $response = $this->client->getShipCargo($ship);
        return $this->render('ships/cargo.html.twig', [
            'ship' => $ship,
            'cargo_details' => $response,
        ]);
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
    #[Route(
        name: 'ship_mounts',
        path: '/ship/mounts',
        methods: ['GET']
    )]
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
            throw new BadRequestException("Waypoint param missing");
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
