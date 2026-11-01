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
        OrderType::TRAVEL->value => Ship\Travel::class,
    ];

    public function __construct(
        private Client\Fleet $fleet,
        private Client\ShipTravel $shipTravel,
        protected Repository\Ship\OrderQueue $orderQueueRepo,
    ) {
    }

    public function createForShipOrder(Entity\Ship\Order $order): AbstractExecutor {
        $type = $order->getType();

        if (array_key_exists($type->value, self::$shipOrderMap)) {
            $class = self::$shipOrderMap[$type->value];
            $queue = $order->getQueue();

            $exec = new $class(
                shipSymbol: $queue->getShipSymbol(),
                fleet: $this->fleet,
                shipTravel: $this->shipTravel,
                orderQueueRepo: $this->orderQueueRepo,
            );

            $exec->setPayload($order->getPayload());
            return $exec;
        }

        throw new \RuntimeException('Unknown order type: ' . $type->name);
    }
}
