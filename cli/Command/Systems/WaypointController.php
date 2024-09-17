<?php

namespace Phparch\SpaceTradersCLI\Command\Systems;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;
use Phparch\SpaceTraders\Value\WaypointSymbol;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(description: "Show details about a waypoint")]
class WaypointController extends CommandController
{
    use TerminalOutputHelper;

    public function required(): array {
        return ['waypoint'];
    }

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Systems::class);

        try {
            $input = $this->getParam('waypoint');
            $wp = new WaypointSymbol($input);

            $waypoint = $client->systemLocation($wp->system, $wp->waypoint);
            $r = new Render\Waypoint($waypoint);
            echo $r->output();

        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }
}