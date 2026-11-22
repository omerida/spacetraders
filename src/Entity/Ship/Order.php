<?php

namespace Phparch\SpaceTraders\Entity\Ship;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Phparch\SpaceTraders\Automation\Ship\OrderType;
use Phparch\SpaceTraders\Automation\Ship\OrderStatus;
use Phparch\SpaceTraders\Repository;

#[ORM\Entity(repositoryClass: Repository\Ship\Order::class)]
#[ORM\Table(name: 'ship_orders')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public private(set) ?int $id = null;

    #[ORM\Column(enumType: OrderStatus::class)]
    public OrderStatus $status = OrderStatus::PENDING;

    public function __construct(
        // Many orders belong to one ship queue
        #[ORM\ManyToOne(targetEntity: OrderQueue::class, inversedBy: 'orders')]
        #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
        public private(set) OrderQueue $queue,
        #[ORM\Column(enumType: OrderType::class)]
        public private(set) OrderType $type,
        /** @var array<string, mixed> */
        #[ORM\Column(type: 'json')]
        public private(set) array $payload = [],
        #[ORM\Column]
        public int $priority = 0
    ) {
    }
}
