<?php

namespace Phparch\SpaceTraders\Entity\Ship;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Phparch\SpaceTraders\Automation\Ship\OrderType;
use Phparch\SpaceTraders\Automation\Ship\OrderStatus;

#[ORM\Entity]
#[ORM\Table(name: 'ship_orders')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: OrderStatus::class)]
    private OrderStatus $status = OrderStatus::PENDING;

    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 255)]
        private string $shipSymbol,
        #[ORM\Column(enumType: OrderType::class)]
        private OrderType $type,
        /** @var array<string, mixed> */
        #[ORM\Column(type: 'json')]
        private array $payload = [],
        #[ORM\Column]
        private int $priority = 0
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function getShipSymbol(): string
    {
        return $this->shipSymbol;
    }

    public function getType(): OrderType
    {
        return $this->type;
    }

    /** @return array<string, mixed> */
    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
