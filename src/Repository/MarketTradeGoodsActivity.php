<?php

namespace Phparch\SpaceTraders\Repository;

use Doctrine\ORM\EntityRepository;
use Phparch\SpaceTraders\Entity;
use Phparch\SpaceTradersRest\Value\Waypoint;

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

    /**
     * @return list<Entity\MarketTradeGoodsActivity>
     */
    public function getLatestForWaypoint(Waypoint\Symbol $symbol): array {
        // We want to return the latest, if we have anything
        $latest = $this->findOneBy(
            criteria: [
                'waypointSymbol' => $symbol->waypoint,
            ],
            orderBy: [
                'timestamp' => 'DESC',
            ]
        );

        if (!$latest instanceof Entity\MarketTradeGoodsActivity) {
            return [];
        }

        $goods = $this->findBy(
            criteria: [
                'waypointSymbol' => $symbol->waypoint,
                'timestamp' => $latest->timestamp,
            ],
            orderBy: [
                'timestamp' => 'DESC',
                'symbol' => 'ASC',
            ]
        );

        if ($goods) {
            /** @var list<Entity\MarketTradeGoodsActivity> $goods */
            return $goods;
        }

        return [];
    }

    /**
     * @return list<Entity\MarketTradeGoodsActivity>
     */
    public function getAllLatest(): array {
        $goods = $this->findAll();
        /** @var list<Entity\MarketTradeGoodsActivity> $goods */
        return $goods;
    }

    /**
     * Return false if nothing saved or the count of new items
     * @param Entity\MarketTradeGoodsActivity[] $goods
     */
    public function saveNewData(array $goods): false|int
    {
        $ts = new \DateTimeImmutable('midnight today');
        $saved = 0;
        foreach ($goods as $good) {
            if (!$this->ifExists($good, $ts)) {
                $this->save($good);
                $saved++;
            }
        }

        if ($saved > 0) {
            $this->getEntityManager()->flush();
            return $saved;
        }

        return false;
    }
}
