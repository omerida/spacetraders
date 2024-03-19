<?php

namespace Phparch\SpaceTradersCLI\Command\MyAgent;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;

class DefaultController extends CommandController
{
    public function handle(): void
    {
        $client = ServiceContainer::get(Client::class);

        $response = $client->MyAgent();

        $response = $response->getBody()->getContents();
        $this->success($response);
    }
}