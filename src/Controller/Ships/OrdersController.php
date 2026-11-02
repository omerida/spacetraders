<?php

namespace Phparch\SpaceTraders\Controller\Ships;

use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Automation\Ship\OrderType;
use Phparch\SpaceTraders\Controller\Trait;
use Phparch\SpaceTraders\Entity\Ship\Order;
use Phparch\SpaceTraders\Interface;
use Phparch\SpaceTraders\Presenter\ShipOrderSummary;
use Phparch\SpaceTraders\Repository;
use Phparch\SpaceTradersRest\Client;
use Phparch\SpaceTradersRest\Value\Goods;
use Phparch\SpaceTradersRest\Value\Ship\FlightMode;
use Phparch\SpaceTradersRest\Value\TradegoodType;
use Psr\Http\Message\ResponseInterface;

class OrdersController implements Interface\RequestAware, Interface\TwigAware
{
    use Trait\RequestAwareController;
    use Trait\TwigAwareController;

    public function __construct(
        private Repository\Ship\OrderQueue $orderQueueRepo,
        private Repository\Ship\Order $orderRepo,
        private Client\Fleet $fleet,
    ) {
    }

    #[Route(
        name: 'delete_ship_order',
        path: '/ship/order/delete',
        methods: ['POST', 'DELETE'],
        strategy: 'application'
    )]
    public function delete(): ResponseInterface
    {
        /**
         * @var array{order?: string} $params
         */
        $params = $this->getRequest()->getParsedBody();

        $shipID = $params['symbol'] ?? null;
        if (!$shipID || !is_string($shipID)) {
            throw new \RuntimeException("Ship POST param missing");
        }

        $orderID = $params['id'] ?? null;
        if (!$orderID) {
            throw new \RuntimeException('Order ID not found');
        }

        // validate it exists
        $order = $this->orderRepo->load($orderID);
        // check that this order belongs to the queue
        $queue = $this->orderQueueRepo->getForOrder($order);
        if ($queue->getShipSymbol() !== $shipID) {
            throw new \RuntimeException("Ship ID does not match");
        }

        $this->orderRepo->delete($order);

        // Prep display of remaining orders
        $queue = $this->orderQueueRepo->getForShip($shipID);
        $orderSummaries = array_map(
            fn($order) => new ShipOrderSummary($order),
            $queue->getOrders()->toArray()
        );
        $response = $this->render(
            '/ships/orders/queue.html.twig',
            [
                'ship' => $this->fleet->getShip($shipID),
                'orders' => $orderSummaries,
            ]
        );

        return $response->withHeader('X-Up-Dismiss-Layer', 'true');
    }
    #[Route(
        name: 'new_ship_order',
        path: '/ship/order/new',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function new(): ResponseInterface
    {
        $shipID = $this->getShipID();
        $queue = $this->orderQueueRepo->getForShip($shipID);

        $orderTypes = array_map(
            fn($type) => ['name' => $type->name, 'value' => $type->value],
            OrderType::cases()
        );

        return $this->render('ships/orders/new.html.twig', [
            'shipID' => $shipID,
            'orderTypes' => $orderTypes,
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

        $stub = strtolower($order->value);
        $stub = ucwords($stub, '_');
        $stub = str_replace('_', '', $stub);
        $method = 'showForm' . $stub;

        if (!method_exists($this, 'showForm' . $stub)) {
            throw new \RuntimeException("Unknown method showForm" . $stub);
        }

        $response = $this->{$method}($order, $shipID);
        assert($response instanceof ResponseInterface);
        return $response;
    }

    private function showFormTravel(OrderType $order, string $shipID): ResponseInterface {
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

    private function showFormExtractResources(OrderType $order, string $shipID): ResponseInterface {
        $goods = array_map(
            fn($good) => ['value' => $good->value, 'name' => $good->name],
            Goods\Symbol::cases()
        );

        usort($goods, fn($a, $b) => $a['value'] <=> $b['value']);

        return $this->render(
            'ships/orders/_form_' . strtolower($order->value) . '.html.twig',
            [
                'shipID' => $shipID,
                'goods' => $goods,
            ]
        );
    }

    private function showFormSellGoods(OrderType $order, string $shipID): ResponseInterface {
        $goods = array_map(
            fn($good) => ['value' => $good->value, 'name' => $good->name],
            Goods\Symbol::cases()
        );

        usort($goods, fn($a, $b) => $a['value'] <=> $b['value']);

        return $this->render(
            'ships/orders/_form_' . strtolower($order->value) . '.html.twig',
            [
                'shipID' => $shipID,
                'goods' => $goods,
            ]
        );
    }

    private function showFormRefuel(OrderType $order, string $shipID): ResponseInterface {
        return $this->render(
            'ships/orders/_form_refuel.html.twig',
            [
                'shipID' => $shipID,
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

        $response = $this->render(
            '/ships/orders/queue.html.twig',
            [
                'ship' => $this->fleet->getShip($shipID),
                'orders' => $orderSummaries,
            ]
        );

        return $response->withHeader('X-Up-Dismiss-Layer', 'true');
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
