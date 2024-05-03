<?php

namespace Phparch\SpaceTradersCLI\Command\Systems;

use CuyZ\Valinor\MapperBuilder;
use CuyZ\Valinor\Normalizer\Format;
use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;

class WaypointController extends CommandController
{
    use TerminalOutputHelper;

    public function required(): array {
        return ['system', 'waypoint'];
    }

    public function handle(): void
    {
        $client = ServiceContainer::get(Client::class);


        try {
            $system = $this->getParam('system');
            $waypoint = $this->getParam('waypoint');

            // @todo system regexp can be stricter, maybe a value object?
            if (!preg_match('/[[:alnum:]-]+/', $system)) {
                throw new \InvalidArgumentException("Symbol can only include letters, numbers, underscores");
            }

            // @todo waypoint regexp can be stricter, maybe a value object?
            if (!preg_match('/[[:alnum:]-]+/', $waypoint)) {
                throw new \InvalidArgumentException("Faction can only include letters, numbers, underscores");
            }

            // get a value object from json
            $response = $client->systemLocation($system, $waypoint);

            $this->outputVar($response);

        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }
}