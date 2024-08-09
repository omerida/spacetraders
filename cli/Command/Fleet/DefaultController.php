<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;
use Phparch\SpaceTradersCLI\Command\HelpInfo;

#[HelpInfo(description: "List all ships")]
class DefaultController extends CommandController
{
    use TerminalOutputHelper;

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $response = $client->ListShips();

        $this->outputVar($response);
    }
}