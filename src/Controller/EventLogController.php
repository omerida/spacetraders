<?php

namespace Phparch\SpaceTraders\Controller;

use Doctrine\ORM\EntityManagerInterface;
use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\Controller\Trait\TwigAwareController;
use Phparch\SpaceTraders\Entity\EventRecord;
use Phparch\SpaceTraders\Interface\RequestAware;
use Phparch\SpaceTraders\Interface\TwigAware;
use Phparch\SpaceTraders\Presenter\EventRecordPresenter;
use Psr\Http\Message\ResponseInterface;

class EventLogController implements RequestAware, TwigAware
{
    use RequestAwareController;
    use TwigAwareController;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws BadRequestException
     */
    #[Route(
        name: 'view_event_log',
        path: '/events/',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function viewEvents(): ResponseInterface
    {
        /** @var EventRecord[] $events */
        $events = $this->entityManager->getRepository(EventRecord::class)
            ->findBy(
                criteria: [],
                orderBy: ['id' => 'DESC'],
                limit: 50,
            );

        $events = array_map(
            fn(EventRecord $record) => new EventRecordPresenter($record),
            $events
        );

        return $this->render('events/list.html.twig', [
            'events' => $events,
        ]);
    }
}
