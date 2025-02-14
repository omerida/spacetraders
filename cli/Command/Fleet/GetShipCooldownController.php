<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use InvalidArgumentException;
use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;
use Phparch\SpaceTradersCLI\Command\HelpInfo;

#[HelpInfo(description: "Get ship cooldown info", params: ['ship symbol'])]
class GetShipCooldownController extends CommandController
{
    use TerminalOutputHelper;

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $args = $this->getArgs();
        $ship = $args[3] ?? null;
        if (!$ship) {
            throw new InvalidArgumentException("Please specify ship symbol as third parameter");
        }
        $response = $client->getShipCooldown($ship);
        $this->outputVar($response);
    }
}
