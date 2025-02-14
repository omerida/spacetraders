<?php

namespace Phparch\SpaceTradersCLI\Command\Systems;

use InvalidArgumentException;
use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTradersCLI\Render;
use Phparch\SpaceTradersCLI\Command\HelpInfo;

#[HelpInfo(description: "Search waypoints, optionally filter by query"
    . " string (type and/or traits)", params: ['systemSymbol', '?type'])]
class WaypointsController extends CommandController
{
    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Systems::class);

        $args = $this->getArgs();
        $system = $args[3] ?? null;

        if (!$system) {
            throw new InvalidArgumentException("Please specify system");
        }

        $type = $args[4] ?? '';

        $rawArgs = $this->input->getRawArgs();
        $query = [];
        if (str_contains($rawArgs[4], '=')) {
            // allow CLI to specify the query string
            $qs = $rawArgs[4];

            parse_str($qs, $query);

            $allowed = ['traits', 'type'];
            $unknown = array_diff(array_keys($query), $allowed);
            if ($unknown) {
                throw new InvalidArgumentException("Uknown query: " . join($unknown));
            }

            if (empty($query)) {
                throw new InvalidArgumentException("Invalid query");
            }
        } elseif (isset($rawArgs[4])) {
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
