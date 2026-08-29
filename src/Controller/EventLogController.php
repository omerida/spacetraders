<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Controller;
use Phparch\SpaceTraders\Entity\EventRecord;
use Phparch\SpaceTraders\Interface;
use Phparch\SpaceTraders\Presenter;
use Phparch\SpaceTraders\Repository;
use Psr\Http\Message\ResponseInterface;

class EventLogController implements Interface\RequestAware, Interface\TwigAware
{
    use Controller\Trait\RequestAwareController;
    use Controller\Trait\TwigAwareController;

    public function __construct(
        private Repository\EventRecord $repository,
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
        return $this->render('events/list.html.twig', [
            'events' => array_map(
                fn(EventRecord $record) => new Presenter\EventRecord($record),
                $this->repository->getLatest(50)
            ),
        ]);
    }
}
