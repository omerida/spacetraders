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
    private ?int $id = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'queue')]
    private Collection $orders;

    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 255)]
        private string $shipSymbol,
        #[ORM\Column(enumType: QueueMode::class)]
        private QueueMode $queueMode,
        #[ORM\Column]
        private int $current = 0
    ){
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShipSymbol(): string
    {
        return $this->shipSymbol;
    }

    public function getQueueMode(): QueueMode
    {
        return $this->queueMode;
    }

    public function getCurrent(): int
    {
        return $this->current;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }
}
