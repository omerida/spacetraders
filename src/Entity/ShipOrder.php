<?php

namespace Phparch\SpaceTraders\Entity;

use Doctrine\DBAL\Types\Types;
use Phparch\SpaceTraders\Automation\ShipOrderType;
use Phparch\SpaceTraders\Automation\ShipOrderStatus;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'ship_orders')]
class ShipOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: ShipOrderStatus::class)]
    private ShipOrderStatus $status = ShipOrderStatus::PENDING;


    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 255)]
        private string $shipSymbol,
        #[ORM\Column(enumType: ShipOrderType::class)]
        private ShipOrderType $type,
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

    public function getStatus(): ShipOrderStatus
    {
        return $this->status;
    }

    public function getShipSymbol(): string
    {
        return $this->shipSymbol;
    }

    public function getType(): ShipOrderType
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
