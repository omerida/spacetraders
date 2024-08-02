<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;
use Phparch\SpaceTradersCLI\Command\HelpInfo;

#[HelpInfo(description: "Get cargo details for a ship", params: ['ship symbol'])]
class GetShipCargoController extends CommandController
{
    use TerminalOutputHelper;

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $args = $this->getArgs();
        $ship = $args[3] ?? null;
        if (!$ship) {
            throw new \InvalidArgumentException("Please specify ship symbol as third parameter");
        }

        $response = $client->getShipCargo($ship);

        $this->outputVar($response);
    }
}