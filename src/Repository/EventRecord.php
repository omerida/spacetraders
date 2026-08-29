<?php

namespace Phparch\SpaceTraders\Repository;

use Doctrine\ORM\EntityRepository;
use Phparch\SpaceTraders\Entity;

/**
 * @extends EntityRepository<Entity\EventRecord>
 */
class EventRecord extends EntityRepository
{
    public function save(
        Entity\EventRecord $entity,
        bool $flush = false
    ): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Entity\EventRecord[]
     */
    public function getLatest(int $limit = 50): array {
        return $this->findBy(
            criteria: [],
            orderBy: ['id' => 'DESC'],
            limit: $limit,
        );
    }
}
