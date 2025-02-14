<?php

namespace Phparch\SpaceTradersCLI\Command\Systems;

use InvalidArgumentException;
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

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Systems::class);

        try {
            $args = $this->getArgs();
            $id = $args[3] ?? null;
            if (!$id) {
                throw new InvalidArgumentException("Please specify waypoint as third parameter");
            }

            if (!preg_match('/[A-Z0-9\-]+/', strtoupper($id))) {
                throw new InvalidArgumentException("Invalid characters in waypoing ID");
            }

            $wp = new WaypointSymbol($id);

            $waypoint = $client->systemLocation($wp->system, $wp->waypoint);
            $r = new Render\Waypoint($waypoint);
            echo $r->output();
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }
}
