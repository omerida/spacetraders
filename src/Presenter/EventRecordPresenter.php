<?php

namespace Phparch\SpaceTraders\Presenter;

use Phparch\SpaceTraders\Entity\EventRecord;

class EventRecordPresenter
{
    public function __construct(
        private EventRecord $event,
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
            case 'Phparch\SpaceTradersRest\Event\ContractAccepted':
                if ($id = $this->event->getData()['id']) {
                    assert(is_string($id));
                    return sprintf('/contracts/get/?id=%s', $id);
                }
        }

        return null;
    }
}
