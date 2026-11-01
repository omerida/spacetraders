<?php

namespace Phparch\SpaceTraders\Automation\Executor\Ship;

use Phparch\SpaceTraders\Presenter\ShipOrderSummary;
use Phparch\SpaceTradersRest\Value\Ship;
use Phparch\SpaceTradersRest\Value\Ship\Status;
use Psr\Http\Message\ResponseInterface;
use Phparch\SpaceTradersRest\Value\Goods\Symbol;

class ExtractResources extends AbstractExecutor
{
    /**
     * @var array{int, value-of<Symbol>}
     */
    private array $keep;
    /**
     * @param array{
     *     keep: array{int, value-of<Symbol>},
     * } $payload
     */
    public function setPayload(array $payload): void
    {
        $this->keep = $payload['keep'];
    }

    public function execute(): void {
        $ship = $this->fleet->getShip($this->shipSymbol);

        if (
            Status::IN_TRANSIT === $ship->nav->status
            || $ship->cooldown->remainingSeconds > 0
        ) {
            return;
        }

        if ($ship->cargo->capacity === 0) {
            $this->advance = true;
        }

        // if we're docked go to orbit
        if (Status::DOCKED === $ship->nav->status) {
            $this->shipTravel->orbitShip($this->shipSymbol);
        }

        $this->shipActions->extractResources($ship->symbol);

        // ship cargo has changed
        $ship = $this->fleet->getShip($this->shipSymbol);
        $this->jettison($ship);
    }

    public function getResponse(): ResponseInterface
    {
        $ship = $this->fleet->getShip($this->shipSymbol);
        $queue = $this->orderQueueRepo->getForShip($this->shipSymbol);
        $orderSummaries = array_map(
            fn($order) => new ShipOrderSummary($order),
            $queue->getOrders()->toArray()
        );
        $response = $this->render(
            template: '/ships/orders/exec_extract_resources.html.twig',
            parameters: [
                'ship' => $ship,
                'orders' => $orderSummaries,
            ]
        );

        $response = $response->withHeader(
            header: 'X-Up-Target',
            value: implode(', ', [
                '.ship-order-queue',
                '.ship-cargo',
                '.ship-cooldown'
            ])
        );

        return $response;
    }

    public function jettison(Ship $ship): void {

        if (!$ship->cargo->inventory) {
            return;
        }

        foreach ($ship->cargo->inventory as $inventory) {
            if (!in_array($inventory->symbol->value, $this->keep)) {
                $this->shipActions->jettisonCargo(
                    ship: $ship->symbol,
                    cargo: $inventory->symbol->value,
                    units: $inventory->units,
                );
            }
        }
    }
}
