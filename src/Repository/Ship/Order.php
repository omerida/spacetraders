<?php

namespace Phparch\SpaceTraders\Repository\Ship;

use Doctrine\ORM\EntityRepository;
use Phparch\SpaceTraders\Entity;

/**
 * @extends EntityRepository<Entity\Ship\Order>
 */
class Order extends EntityRepository
{
    public function delete(
        Entity\Ship\Order $entity,
        bool $flush = true
    ): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function load(int $id): Entity\Ship\Order {
        $order = $this->findOneBy(['id' => $id]);

        assert($order instanceof Entity\Ship\Order);

        return $order;
    }

    public function save(
        Entity\Ship\Order $entity,
        bool $flush = true
    ): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
