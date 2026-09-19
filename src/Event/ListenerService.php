<?php

namespace Phparch\SpaceTraders\Event;

use Doctrine\ORM\EntityManagerInterface;
use Phparch\SpaceTraders\Entity;
use Phparch\SpaceTraders\Repository;
use Phparch\SpaceTradersRest\Event\ContractAccepted;
use Phparch\SpaceTradersRest\Event\ContractCargoDelivered;
use Phparch\SpaceTradersRest\Event\ContractFulfilled;
use Phparch\SpaceTradersRest\Event\SystemMarketData;

/**
 * Any public method with the #[Listener] attribute is automatically
 * registered in the app's ServiceContainer.
 *
 * @see config/services.php
 */
class ListenerService
{
    /**
     * Record when player accepts a contract.
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function onContractAccepted(ContractAccepted $event): void {
        $contract = $event->accepted->contract;

        $record = new Entity\EventRecord(
            name: "Contract Accepted",
            source: $event::class,
            description: <<<EOF
            {$event->accepted->agent->symbol} accepted a {$contract->type->value} contract.
            The contract expires on {$contract->expiration->format(\DateTime::ATOM)}.
            EOF
        );

        $record->setDataFromArray([
            'id' => $contract->id,
            'terms' => $contract->terms,
            'expiration' => $contract->expiration->format(\DateTime::ATOM),
            'type' => $contract->type->value,
        ]);

         /** @var Repository\EventRecord $repo */
        $repo = $this->entityManager->getRepository(Entity\EventRecord::class);
        $repo->save($record);
    }

    public function onContractCargoDelivered(ContractCargoDelivered $event): void {
        $contract = $event->deliverCargo->contract;

        $record = new Entity\EventRecord(
            name: "Contract Cargo Delivered",
            source: $event::class,
            description: <<<EOF
            One of our ships delivered cargo for a {$contract->type->value} contract.
            The contract expires on {$contract->expiration->format(\DateTime::ATOM)}.
            EOF
        );

        $record->setDataFromArray([
            'id' => $contract->id,
            'terms' => $contract->terms,
            'expiration' => $contract->expiration->format(\DateTime::ATOM),
            'type' => $contract->type->value,
            'cargo' => $event->deliverCargo->cargo,
        ]);

         /** @var Repository\EventRecord $repo */
        $repo = $this->entityManager->getRepository(Entity\EventRecord::class);
        $repo->save($record);
    }

    public function onContractFulfilled(ContractFulfilled $event): void {
        $contract = $event->fulfilled->contract;

        $record = new Entity\EventRecord(
            name: "Contract Fulfilled",
            source: $event::class,
            description: <<<EOF
            {$event->fulfilled->agent->symbol} accepted a {$contract->type->value} contract.
            The contract expired on {$contract->expiration->format(\DateTime::ATOM)}.
            EOF
        );

        $record->setDataFromArray([
            'id' => $contract->id,
            'terms' => $contract->terms,
            'expiration' => $contract->expiration->format(\DateTime::ATOM),
            'type' => $contract->type->value,
        ]);

         /** @var Repository\EventRecord $repo */
        $repo = $this->entityManager->getRepository(Entity\EventRecord::class);
        $repo->save($record);
    }


    /**
     * Save trade good prices, activity when we get detailed information from
     * a ship at a marketplace.
     */
    public function onSystemMarketData(SystemMarketData $marketData): void {
        $goods = Entity\MarketTradeGoodsActivity::fromTradeGoodsValue(
            $marketData->market->symbol,
            $marketData->market->tradeGoods,
            new \DateTimeImmutable('now')
        );

        /** @var Repository\MarketTradeGoodsActivity $repo */
        $repo = $this->entityManager->getRepository(
            Entity\MarketTradeGoodsActivity::class
        );
        $saved = $repo->saveNewData($goods);

        if ($saved) {
            $record = new Entity\EventRecord(
                name: "Saved market data ",
                source: __CLASS__ . '::' . __FUNCTION__,
                description: "Saved market trade good activity data for "
                    . $marketData->market->symbol
            );
            $record->setDataFromArray([
                'waypointSymbol' => $marketData->market->symbol->waypoint
            ]);

            /** @var Repository\EventRecord $repo */
            $repo = $this->entityManager->getRepository(Entity\EventRecord::class);
            $repo->save($record, flush: true);
        }
    }
}
