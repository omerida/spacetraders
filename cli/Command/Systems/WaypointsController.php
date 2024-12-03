<?php

namespace Phparch\SpaceTradersCLI\Command\Systems;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;
use Phparch\SpaceTradersCLI\Render;
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

        $rawArgs = $this->input->getRawArgs();
        if (str_contains($rawArgs[4], '=')) {
            // allow CLI to specify the query string
            $qs = $rawArgs[4];
            $query = [];
            parse_str($qs, $query);

            if (empty($query)) {
                throw new \InvalidArgumentException("Invalid query");
            }
        } else {
            $query = ['type' => $type];
        }
        try {
            $response = $client->waypoints($system, $query);

            foreach ($response->waypoints as $waypoint) {
                $r = new Render\Waypoint($waypoint);
                echo $r->output();
            }
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }
}
