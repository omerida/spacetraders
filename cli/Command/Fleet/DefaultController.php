<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(description: "List all ships")]
class DefaultController extends CommandController
{
    use TerminalOutputHelper;

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $response = $client->ListShips();

        if (!$response->ships) {
            echo "No ships found.";
            return;
        }

        foreach ($response->ships as $ship) {
            $r = new Render\Ship($ship);
            echo $r->output();
        }
    }
}
