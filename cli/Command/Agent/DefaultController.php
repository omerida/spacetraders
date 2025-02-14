<?php

namespace Phparch\SpaceTradersCLI\Command\Agent;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\APIException;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;

#[HelpInfo(description: "Show details about the registered agent.")]
class DefaultController extends CommandController
{
    use TerminalOutputHelper;

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Agents::class);

        try {
            $response = $client->MyAgent();
            $agent = new Render\Agent($response);
            echo $agent->output();
        } catch (APIException $ex) {
            $error = new Render\Exception($ex);
            echo $error->output();
        }
    }
}
