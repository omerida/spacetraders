<?php

namespace Phparch\SpaceTraders\Automation\Executor\Ship;

use Phparch\SpaceTraders\Presenter\ShipOrderSummary;
use Phparch\SpaceTradersRest\Value\Ship\Status;
use Phparch\SpaceTradersRest\Value\Ship\FlightMode;
use Phparch\SpaceTradersRest\Value\Waypoint;
use Phparch\SpaceTradersRest\Value\Fleet\RefuelShip;
use Psr\Http\Message\ResponseInterface;

class Refuel extends AbstractExecutor
{
    private ?RefuelShip $refuel = null;

    /**
     * @param array{
     *     destination?: string,
     *     flightmode?: string
     * } $payload
     */
    public function setPayload(array $payload): void {
        /* no payload for this command */
    }

    public function execute(): void {
        $ship = $this->fleet->getShip($this->shipSymbol);

        // no need to buy fuel
        if ($ship->fuel->current === $ship->fuel->capacity) {
            $this->advance = true;
            return;
        }

        // dock first
        if (Status::DOCKED !== $ship->nav->status) {
            $this->shipTravel->dockShip($this->shipSymbol);
        }

        $this->refuel = $this->shipActions->refuelShip($ship->symbol);
        $this->advance = true;
    }

    public function getResponse(): ResponseInterface
    {
        $ship = $this->fleet->getShip($this->shipSymbol);
        $queue = $this->orderQueueRepo->getForShip($this->shipSymbol);
        $orderSummaries = array_map(
            fn($order) => new ShipOrderSummary($order),
            $queue->getOrders()->toArray()
        );

        assert($this->refuel instanceof RefuelShip);

        $response = $this->render(
            template: '/ships/orders/exec_refuel.html.twig',
            parameters: [
                'ship' => $ship,
                'orders' => $orderSummaries,
                'agent' => $this->refuel->agent,
                'fuel' => $this->refuel->fuel,
                'transaction' => $this->refuel->transaction
            ]
        );

        $response = $response->withHeader(
            header: 'X-Up-Target',
            value: implode(', ', [
                '.ship-order-queue',
                '.ship-fuel',
                '.order-result'
            ])
        );

        return $response;
    }
}
