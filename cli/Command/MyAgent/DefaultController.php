<?php

namespace Phparch\SpaceTradersCLI\Command\MyAgent;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;

class DefaultController extends CommandController
{
    use TerminalOutputHelper;

    public function handle(): void
    {
        $client = ServiceContainer::get(Client::class);

        $response = $client->MyAgent();

        $this->outputVar($response);

    }
}