<?php

namespace Phparch\SpaceTraders\Event;

use Doctrine\ORM\EntityManagerInterface;
use Phparch\SpaceTraders\Entity\EventRecord;
use Phparch\SpaceTradersRest\Event\ContractAccepted;
use Crell\Tukio\Listener;

/**
 * Any public method with the #[Listener] attribute is automatically
 * registered in the app's ServiceContainer.
 *
 * @see config/services.php
 */
class ListenerService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function onContractAccepted(ContractAccepted $event): void {
        $contract = $event->accepted->contract;

        $record = new EventRecord(
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

        $this->entityManager->persist($record);
        $this->entityManager->flush();
    }
}
