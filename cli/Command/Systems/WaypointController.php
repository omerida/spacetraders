<?php

namespace Phparch\SpaceTradersCLI\Command\Systems;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;
use Phparch\SpaceTraders\Value\WaypointSymbol;

class WaypointController extends CommandController
{
    use TerminalOutputHelper;

    public function required(): array {
        return ['waypoint'];
    }

    public function handle(): void
    {
        $client = ServiceContainer::get(Client::class);

        try {
            $input = $this->getParam('waypoint');
            $wp = new WaypointSymbol($input);

            $response = $client->systemLocation($wp->system, $wp->waypoint);
            $this->outputVar($response);

        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }
}