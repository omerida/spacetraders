<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(
    description: "Set the nav mode for ship travel",
    params: ['ship symbol', 'nav mode']
)]
class ShipNavController extends CommandController
{
    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $args = $this->getArgs();

        $ship = $args[3] ?? null;
        if (!$ship) {
            throw new \InvalidArgumentException("Please specify ship symbol as third parameter");
        }

        // TODO valiate this is an allowed mode
        $mode = strtoupper($args[4] ?? '');
        if (!$mode) {
            throw new \InvalidArgumentException("Please specify type as fourth parameter");
        }

        $response = $client->setNavMode($ship, $mode);
        $nav = new Render\Ship\Nav($response);
        echo $nav->output();
    }
}
