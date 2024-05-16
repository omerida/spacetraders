<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;

class GetShipController extends CommandController
{
    use TerminalOutputHelper;

    public function required(): array {
        return ['ship'];
    }

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);
        $ship = $this->getParam('ship');

        $response = $client->getShip($ship);

        $this->outputVar($response);
    }
}