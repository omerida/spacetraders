<?php

namespace Phparch\SpaceTraders\Presenter;

use Phparch\SpaceTraders\Entity;

class EventRecord
{
    public function __construct(
        private Entity\EventRecord $event,
    ) {
    }

    // Delegate getters to the underlying entity
    public function getId(): ?int
    {
        return $this->event->getId();
    }

    public function getName(): string
    {
        return $this->event->getName();
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->event->getCreatedAt();
    }

    public function getSource(): ?string
    {
        return $this->event->getSource();
    }

    public function getDescription(): ?string
    {
        return $this->event->getDescription();
    }

    // Context-aware URL resolver logic
    public function getUrl(): ?string
    {
        switch ($this->event->getSource()) {
            case 'Phparch\SpaceTraders\Event\ListenerService::onSystemMarketData':
                if ($id = $this->event->getData()['waypointSymbol']) {
                    assert(is_string($id));
                    return sprintf('/systems/market?id=%s', $id);
                }
                break;
            case 'Phparch\SpaceTradersRest\Event\ContractAccepted':
                if ($id = $this->event->getData()['id']) {
                    assert(is_string($id));
                    return sprintf('/contracts/get/?id=%s', $id);
                }
                break;
            case 'Phparch\SpaceTradersRest\Event\ContractFulfilled':
                if ($id = $this->event->getData()['id']) {
                    assert(is_string($id));
                    return sprintf('/contracts/get/?id=%s', $id);
                }
                break;
            case 'Phparch\SpaceTradersRest\Event\ContractCargoDelivered':
                if ($id = $this->event->getData()['id']) {
                    assert(is_string($id));
                    return sprintf('/contracts/get/?id=%s', $id);
                }
                break;
        }

        return null;
    }
}
