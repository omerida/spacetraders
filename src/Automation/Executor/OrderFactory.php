<?php

namespace Phparch\SpaceTraders\Automation\Executor;

use Phparch\SpaceTraders\Automation\Ship\OrderType;
use Phparch\SpaceTraders\Entity;
use Phparch\SpaceTraders\Repository;
use Phparch\SpaceTradersRest\Client;
use Phparch\SpaceTraders\Automation\Executor\Ship\AbstractExecutor;

class OrderFactory
{
    /**
     * @var array<value-of<OrderType>, class-string<AbstractExecutor>>
     */
    private static array $shipOrderMap = [
        OrderType::EXTRACT_RESOURCES->value => Ship\ExtractResources::class,
        OrderType::REFUEL->value => Ship\Refuel::class,
        OrderType::SELL_GOODS->value => Ship\SellGoods::class,
        OrderType::TRAVEL->value => Ship\Travel::class,
    ];

    public function __construct(
        private Client\Fleet $fleet,
        private Client\ShipTravel $shipTravel,
        private Client\ShipActions $shipActions,
        protected Repository\Ship\OrderQueue $orderQueueRepo,
    ) {
    }

    public function createForShipOrder(Entity\Ship\Order $order): AbstractExecutor {
        $type = $order->getType();

        if (array_key_exists($type->value, self::$shipOrderMap)) {
            $class = self::$shipOrderMap[$type->value];
            $queue = $order->getQueue();

            $exec = new $class(
                shipSymbol: $queue->shipSymbol,
                fleet: $this->fleet,
                shipTravel: $this->shipTravel,
                shipActions: $this->shipActions,
                orderQueueRepo: $this->orderQueueRepo,
            );

            $exec->setPayload($order->getPayload());
            return $exec;
        }

        throw new \RuntimeException('Unknown order type: ' . $type->name);
    }
}
