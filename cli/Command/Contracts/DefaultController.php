<?php

namespace Phparch\SpaceTradersCLI\Command\Contracts;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;
use Phparch\SpaceTradersCLI\Command\HelpInfo;

#[HelpInfo(description: "Show My Contracts")]
class DefaultController extends CommandController
{
    use TerminalOutputHelper;

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Contracts::class);

        $response = $client->MyContracts();

        $this->outputVar($response);
    }
}