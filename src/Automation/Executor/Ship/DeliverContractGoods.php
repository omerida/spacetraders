<?php

namespace Phparch\SpaceTraders\Automation\Executor\Ship;

use Phparch\SpaceTradersRest\Value\Ship\Status;
use Psr\Http\Message\ResponseInterface;
use Phparch\SpaceTradersRest\Value\Goods;

class DeliverContractGoods extends AbstractExecutor
{
    /** @var Goods\Symbol[] $goods*/
    private array $goods = [];

    private string $contract;

    /**
     * @param array{
     *     deliver?: array<int, value-of<Goods\Symbol>>,
     *     contract?: string
     * } $payload
     */
    public function setPayload(array $payload): void {
        if (empty($payload['deliver'])) {
            return;
        }

        if (empty($payload['contract'])) {
            throw new \RuntimeException('Missing contract');
        }

        $this->contract = trim($payload['contract']);
        assert(is_string($this->contract));

        foreach ($payload['deliver'] as $g) {
            if ($good = Goods\Symbol::tryFrom($g)) {
                $this->goods[] = $good;
            } else {
                throw new \RuntimeException("Invalid good symbol");
            }
        }
    }

    public function execute(): void
    {
        $ship = $this->fleet->getShip($this->shipSymbol);

        // have cargo?
        if ($ship->cargo->units === 0) {
            $this->advance = true;
            return;
        }

        // dock first
        if (Status::DOCKED !== $ship->nav->status) {
            $this->shipTravel->dockShip($this->shipSymbol);
        }

        foreach ($ship->cargo->inventory as $item) {
            if (!in_array($item->symbol, $this->goods)) {
                continue;
            }

            // sell indicated goods
            $this->contracts->deliverCargo(
                id: $this->contract,
                shipSymbol: $ship->symbol,
                good: $item->symbol,
                units: $item->units,
            );
        }

        $this->advance = true;
    }

    public function getResponse(): ResponseInterface
    {
        $ship = $this->fleet->getShip($this->shipSymbol);

        // TODO need a way to collect transactions and show all of them
        return $this->render('ships/ship-sell-goods.html.twig', [
            'cargo' => $ship->cargo,
        ]);
    }
}
