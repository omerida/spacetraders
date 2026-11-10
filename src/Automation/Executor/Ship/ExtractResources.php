<?php

namespace Phparch\SpaceTraders\Automation\Executor\Ship;

use Phparch\SpaceTraders\Entity\Ship\Order;
use Phparch\SpaceTraders\Presenter\ShipOrderSummary;
use Phparch\SpaceTradersRest\Value\Ship;
use Phparch\SpaceTradersRest\Value\Ship\Status;
use Psr\Http\Message\ResponseInterface;
use Phparch\SpaceTradersRest\Value\Goods\Symbol;

class ExtractResources extends AbstractExecutor
{
    private ?\Phparch\SpaceTradersRest\Value\Fleet\ExtractResources $details = null;

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

        // ship cargo has changed
        $this->details = $this->shipActions->extractResources($ship->symbol);
        $ship = $this->fleet->getShip($this->shipSymbol);
        $this->jettison($ship);
    }

    public function getResponse(): ResponseInterface
    {
        $ship = $this->fleet->getShip($this->shipSymbol);
        $queue = $this->orderQueueRepo->getForShip($this->shipSymbol);
        $orderSummaries = $queue->orders
            ->map(fn(Order $order) => new ShipOrderSummary($order))
            ->toArray();
        $response = $this->render(
            template: '/ships/orders/results/extract_resources.html.twig',
            parameters: [
                'ship' => $ship,
                'orders' => $orderSummaries,
                'details' => $this->details,
            ]
        );

//        $response = $response->withHeader(
//            header: 'X-Up-Target',
//            value: implode(', ', [
//                '.order-result'
//            //                '.ship-cargo',
//            //                '.ship-cooldown:maybe',
//            //                '.order-result:maybe'
//            ])
//        );

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
