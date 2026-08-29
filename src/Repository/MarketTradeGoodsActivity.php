<?php

namespace Phparch\SpaceTraders\Repository;

use Doctrine\ORM\EntityRepository;
use Phparch\SpaceTraders\Entity;

/**
 * @extends EntityRepository<MarketTradeGoodsActivity>
 */
class MarketTradeGoodsActivity extends EntityRepository
{
    public function save(
        Entity\MarketTradeGoodsActivity $entity,
        bool $flush = false
    ): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function ifExists(
        Entity\MarketTradeGoodsActivity $entity,
        \DateTimeImmutable $ts,
    ): false|Entity\MarketTradeGoodsActivity {
        $exists = $this->findOneBy(
            criteria: [
                'waypointSymbol' => $entity->waypointSymbol->waypoint,
                'symbol' => $entity->symbol->value,
                'timestamp' => $ts,
            ]
        );
        if ($exists instanceof Entity\MarketTradeGoodsActivity) {
            return $exists;
        }

        return false;
    }
}
