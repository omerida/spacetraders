<?php

namespace Phparch\SpaceTradersCLI\Command\My;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;

class ContractsController extends CommandController
{
    use TerminalOutputHelper;

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Contracts::class);

        $response = $client->MyContracts();

        $this->outputVar($response);
    }
}