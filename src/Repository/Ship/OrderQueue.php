<?php

namespace Phparch\SpaceTraders\Repository\Ship;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @return Entity\Ship\OrderQueue[]
     */
    public function withOrdersPending(int $limit = 10): array {

        // TODO check that the queue status is not PAUSED
        $qb = $this->getEntityManager()->createQueryBuilder();
        $or = $qb->expr()->orX();
        $or->add(
            $qb->expr()->isNull('q.nextCheck')
        );
        $or->add(
            $qb->expr()->lt('q.nextCheck', 'CURRENT_TIMESTAMP()')
        );


        $query = $qb ->select('q')
            ->from(Entity\Ship\OrderQueue::class, 'q')
            ->where($or)
            ->orderBy('q.nextCheck', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();

        /** @var Entity\Ship\OrderQueue[] $queues */
        $queues = $query->getResult();
        return $queues;
    }
}
