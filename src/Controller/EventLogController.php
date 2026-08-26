<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\Controller\Trait\TwigAwareController;
use Phparch\SpaceTraders\Interface\RequestAware;
use Phparch\SpaceTraders\Interface\TwigAware;
use Psr\Http\Message\ResponseInterface;

class EventLogController implements RequestAware, TwigAware
{
    use RequestAwareController;
    use TwigAwareController;

    public function __construct(
        //private int $foo,
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
        $events = [];

        return $this->render('events/list.html.twig', [
            'events' => $events,
        ]);
    }
}
