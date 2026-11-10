<?php

namespace Phparch\SpaceTraders\Entity\Ship;

use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Phparch\SpaceTraders\Automation\Ship\QueueMode;
use Phparch\SpaceTraders\Repository;

#[ORM\Entity(repositoryClass: Repository\Ship\OrderQueue::class)]
#[ORM\Table(name: 'ship_order_queue')]
class OrderQueue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public private(set) ?int $id = null;

    // phpcs:disable
    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'queue')]
    public Collection $orders {
        get => $this->orders;
    }
    // phpcs:enable

    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 255)]
        public private(set) string $shipSymbol,
        #[ORM\Column(enumType: QueueMode::class)]
        public private(set) QueueMode $queueMode,
        #[ORM\Column]
        public int $current = 0
    ){
        $this->orders = new ArrayCollection();
    }
}
