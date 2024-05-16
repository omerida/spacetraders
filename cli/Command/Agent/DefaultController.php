<?php

namespace Phparch\SpaceTradersCLI\Command\Agent;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;

class DefaultController extends CommandController
{
    use TerminalOutputHelper;

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Agents::class);

        $response = $client->MyAgent();

        $this->outputVar($response);
    }
}