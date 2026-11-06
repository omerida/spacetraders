<?php

namespace Phparch\SpaceTraders\Automation\Executor\Ship;

use Phparch\SpaceTradersRest\Value\Ship\Status;
use Psr\Http\Message\ResponseInterface;
use Phparch\SpaceTradersRest\Value\Goods;

class SellGoods extends AbstractExecutor
{
    /** @var Goods\Symbol[] $goods*/
    private array $goods = [];

    /**
     * @param array{
     *     sell?: array<int, value-of<Goods\Symbol>>,
     * } $payload
     */
    public function setPayload(array $payload): void {

        if (empty($payload['sell'])) {
            return;
        }

        foreach ($payload['sell'] as $g) {
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
            $this->shipActions->sellCargo(
                ship: $ship->symbol,
                cargo: $item->symbol->value,
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
