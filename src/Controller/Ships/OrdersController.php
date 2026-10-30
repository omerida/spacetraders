<?php

namespace Phparch\SpaceTraders\Controller\Ships;

use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Entity\Ship\Order;
use Phparch\SpaceTraders\Interface;
use Phparch\SpaceTraders\Controller\Trait;
use Phparch\SpaceTraders\Presenter\ShipOrderSummary;
use Phparch\SpaceTradersRest\Value\Ship\FlightMode;
use Psr\Http\Message\ResponseInterface;
use Phparch\SpaceTraders\Repository;
use Phparch\SpaceTraders\Automation\Ship\OrderType;

class OrdersController implements Interface\RequestAware, Interface\TwigAware
{
    use Trait\RequestAwareController;
    use Trait\TwigAwareController;

    public function __construct(
        private Repository\Ship\OrderQueue $orderQueueRepo,
        private Repository\Ship\Order $orderRepo,
    ) {
    }

    #[Route(
        name: 'new_ship_order',
        path: '/ship/order/new',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function newOrder(): ResponseInterface
    {
        $shipID = $this->getShipID();
        $queue = $this->orderQueueRepo->getForShip($shipID);

        return $this->render('ships/orders/new.html.twig', [
            'shipID' => $shipID,
        ]);
    }

    #[Route(
        name: 'form_ship_order',
        path: '/ship/order/form',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function showForm(): ResponseInterface
    {
        $shipID = $this->getShipID();

        /**
         * @var array{order?: string} $query
         */
        $query = $this->getRequest()->getQueryParams();
        $order = $query['order'] ?? null;
        if (!$order) {
            throw new \RuntimeException('Order not found');
        }

        if (!($order = OrderType::tryFrom($order))) {
            throw new \RuntimeException("Unknown order type.");
        }

        $flightModes = array_map(
            fn($case) => ['value' => $case->value, 'name' => $case->name],
            FlightMode::cases()
        );

        return $this->render(
            'ships/orders/_form_' . strtolower($order->value) . '.html.twig',
            [
                'shipID' => $shipID,
                'flightModes' => $flightModes,
                'flightMode' => FlightMode::CRUISE->value
            ]
        );
    }

    #[Route(
        name: 'create_ship_order',
        path: '/ship/order/create',
        methods: ['POST'],
        strategy: 'application'
    )]
    public function create(): ResponseInterface {
        /**
         * @var array{
         *  shipID ?: string,
         *  type ?: string,
         *  payload ?: array<string, mixed>
         * } $post
         */
        $post = (array) $this->getRequest()->getParsedBody();
        $shipID = $post['shipID'] ?? null;
        if (!$shipID || !is_string($shipID)) {
            throw new \RuntimeException("Ship POST param missing");
        }

        $type = $post['type'] ?? null;
        if (!$type || !($type = OrderType::tryFrom($type))) {
            throw new \RuntimeException("Unknown order type.");
        }

        $payload = $post['payload'] ?? [];

        $order = new Order(
            queue: $this->orderQueueRepo->getForShip($shipID),
            type: $type,
            payload: $payload
        );

        $this->orderRepo->save($order, flush: true);

        // Order Queue
        $queue = $this->orderQueueRepo->getForShip($shipID);
        $orderSummaries = array_map(
            fn($order) => new ShipOrderSummary($order),
            $queue->getOrders()->toArray()
        );

        return $this->render(
            '/ships/orders/queue.html.twig',
            [
                'ship' => $shipID,
                'orders' => $orderSummaries,
            ]
        );
    }

    private function getShipID(): string
    {
        $query = $this->getRequest()->getQueryParams();
        $id = $query['ship'] ?? null;

        if (!$id || !is_string($id)) {
            throw new \RuntimeException("Ship GET param missing");
        }

        return $id;
    }
}
