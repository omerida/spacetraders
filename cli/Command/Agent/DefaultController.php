<?php

namespace Phparch\SpaceTradersCLI\Command\Agent;

use Minicli\Command\CommandController;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;

#[HelpInfo(description: "Show details about the registered agent.")]
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
