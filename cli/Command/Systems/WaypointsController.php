<?php

namespace Phparch\SpaceTradersCLI\Command\Systems;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;
use Phparch\SpaceTraders\Value\WaypointSymbol;
use Phparch\SpaceTradersCLI\Command\HelpInfo;

#[HelpInfo(description: "Search waypoints, optionally by type", params: ['systemSymbol', '?type'])]
class WaypointsController extends CommandController
{
    use TerminalOutputHelper;

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Systems::class);

        $args = $this->getArgs();
        $system = $args[3] ?? null;
        $type = $args[4] ?? '';

        try {
            $response = $client->waypoints($system, $type );
            $this->outputVar($response);

        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }
}