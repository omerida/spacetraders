<?php

namespace Phparch\SpaceTraders\Automation\Executor\Ship;

use Phparch\SpaceTraders\Presenter\ShipOrderSummary;
use Phparch\SpaceTradersRest\Value\Ship\Status;
use Phparch\SpaceTradersRest\Value\Ship\FlightMode;
use Phparch\SpaceTradersRest\Value\Waypoint;
use Psr\Http\Message\ResponseInterface;

class Travel extends AbstractExecutor
{
    private Waypoint\Symbol $waypoint;
    private FlightMode $flightMode;

    /**
     * @param array{
     *     destination?: string,
     *     flightmode?: string
     * } $payload
     */
    public function setPayload(array $payload): void
    {
        if (!isset($payload['destination'])) {
            throw new \InvalidArgumentException("destination is required");
        }
        if (!isset($payload['flightmode'])) {
            throw new \InvalidArgumentException("Flight mode is required");
        }

        $this->waypoint = new Waypoint\Symbol($payload['destination']);
        if (!($mode = FlightMode::tryFrom($payload['flightmode']))) {
            throw new \InvalidArgumentException(
                "Invalid flight mode: " . $payload['flightmode']
            );
        }

        $this->flightMode = $mode;
    }

    public function execute(): void {
        $ship = $this->fleet->getShip($this->shipSymbol);

        $current = $ship->nav->waypointSymbol;

        // 3- Are we already at expected waypoint
        if ($current === $this->waypoint) {
            $this->advance = true;
            return;
        }

        // 2 - if we're docked go to orbit
        if (Status::DOCKED === $ship->nav->status) {
            $this->shipTravel->orbitShip($this->shipSymbol);
        }

        // 1 - set to the desired travel mode
        if ($this->flightMode !== $ship->nav->flightMode) {
            $this->shipTravel->setNavMode(
                $this->shipSymbol,
                $this->flightMode->value,
            );
        }

        // 0 - blastoff
        $this->shipTravel->navigateShip(
            $this->shipSymbol,
            $this->waypoint
        );

        // go on to the next order
        $this->advance = true;
    }

    public function getResponse(): ResponseInterface
    {
        $ship = $this->fleet->getShip($this->shipSymbol);

        $queue = $this->orderQueueRepo->getForShip($this->shipSymbol);
        $orderSummaries = array_map(
            fn($order) => new ShipOrderSummary($order),
            $queue->orders->toArray()
        );

        $response = $this->render(
            template: '/ships/orders/results/travel.html.twig',
            parameters: [
                'ship' => $ship,
                'orders' => $orderSummaries,
            ]
        );

        return $response;
    }
}
