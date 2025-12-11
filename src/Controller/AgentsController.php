<?php

namespace Phparch\SpaceTraders\Controller;

use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\Interface\RequestAware;

class AgentsController implements RequestAware
{
    use RequestAwareController;

    public function __construct(
        private Client\Agents $client,
    ) {
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'my_agent', path: '/my/agent', methods: ['GET'])]
    public function myAgent(): array
    {
        return (array) $this->client->myAgent();
    }
}
