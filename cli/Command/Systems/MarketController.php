<?php

namespace Phparch\SpaceTradersCLI\Command\Systems;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Value\WaypointSymbol;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(
    description: "Get market data for a location.",
    params: ['waypoint symbol']
)]
class MarketController extends CommandController
{
    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Systems::class);

        try {
            $args = $this->getArgs();
            $id = $args[3] ?? null;
            if (!$id) {
                throw new \InvalidArgumentException("Please specify waypoint as third parameter");
            }

            if (!preg_match('/[A-Z0-9\-]+/', strtoupper($id))) {
                throw new \InvalidArgumentException("Invalid characters in waypoing ID");
            }

            $wp = new WaypointSymbol($id);
            $market = $client->market($wp->system, $wp->waypoint);

            $r = new Render\Market($market);
            echo $r->output();
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }
}
