<?php

namespace Phparch\SpaceTraders\Repository\Ship;

use Doctrine\ORM\EntityRepository;
use Phparch\SpaceTraders\Automation\Ship\QueueMode;
use Phparch\SpaceTraders\Entity;

/**
 * @extends EntityRepository<Entity\Ship\OrderQueue>
 */
class OrderQueue extends EntityRepository
{
    public function save(
        Entity\Ship\OrderQueue $entity,
        bool $flush = false
    ): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getForShip(string $shipSymbol): Entity\Ship\OrderQueue {
        $match = $this->findOneBy(
            ['shipSymbol' => $shipSymbol]
        );

        if ($match) {
            return $match;
        }

        // auto-create one
        $queue = new Entity\Ship\OrderQueue(
            $shipSymbol,
            QueueMode::PAUSED
        );

        $this->save($queue, flush: true);
        if ($match =  $this->findOneBy(['shipSymbol' => $shipSymbol])) {
            return $match;
        }

        throw new \RuntimeException("Could not create new queue for ship");
    }
}
