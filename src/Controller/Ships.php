<?php

namespace Phparch\SpaceTraders\Controller;

use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Psr\Http\Message\ServerRequestInterface;

class Ships extends RequestAwareController
{
    public function __construct(
        private Client\Fleet $client,
    ) {
    }

    /**
     * @return array<mixed>
     */
    #[Route(name: 'my_ships', path: '/my/ships', methods: ['GET'])]
    public function myAgent(): array
    {
        return (array) $this->client->listShips();
    }
}
