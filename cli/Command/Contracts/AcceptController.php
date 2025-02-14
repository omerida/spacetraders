<?php

namespace Phparch\SpaceTradersCLI\Command\Contracts;

use InvalidArgumentException;
use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(description: "Accept a contract", params: ["contract ID"])]
class AcceptController extends CommandController
{
    use TerminalOutputHelper;

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Contracts::class);

        $args = $this->getArgs();
        $id = $args[3] ?? null;
        if (!$id) {
            throw new InvalidArgumentException("Please specify contract id as third parameter");
        }

        if (!ctype_alnum($id)) {
            throw new InvalidArgumentException("Contract id should be alphanuumeric.");
        }

        $response = $client->accept($id);

        // show the contract
        $renderer = new Render\Contract($response->contract);
        echo $renderer->output();
    }
}
