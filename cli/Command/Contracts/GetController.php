<?php

namespace Phparch\SpaceTradersCLI\Command\Contracts;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(description: "Get contract details", params: ["contract ID"])]
class GetController extends CommandController
{
    use TerminalOutputHelper;

    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Contracts::class);

        $args = $this->getArgs();
        $id = $args[3] ?? null;
        if (!$id) {
            throw new \InvalidArgumentException("Please specify contract id as third parameter");
        }

        if (!ctype_alnum($id)) {
            throw new \InvalidArgumentException("Contract id should be alphanuumeric.");
        }

        $contract = $client->details($id);

        // show the contract
        $renderer = new Render\Contract($contract);
        echo $renderer->output();
    }
}