<?php

namespace Phparch\SpaceTraders\Entity\Ship;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Phparch\SpaceTraders\Automation\Ship\QueueMode;

#[ORM\Entity]
#[ORM\Table(name: 'ship_order_queue')]
class OrderQueue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 255)]
        private string $shipSymbol,
        #[ORM\Column(enumType: QueueMode::class)]
        private QueueMode $queueMode,
        #[ORM\Column]
        private int $current = 0
    ){
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
}
