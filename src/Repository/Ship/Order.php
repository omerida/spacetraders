<?php

namespace Phparch\SpaceTraders\Repository\Ship;

use Doctrine\ORM\EntityRepository;
use Phparch\SpaceTraders\Entity;

/**
 * @extends EntityRepository<Entity\Ship\Order>
 */
class Order extends EntityRepository
{
    public function save(
        Entity\Ship\Order $entity,
        bool $flush = false
    ): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
