<?php

namespace Phparch\SpaceTradersCLI\Command\Register;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;

class DefaultController extends CommandController
{
    public function required(): array {
        return ['symbol', 'faction'];
    }

    public function handle(): void
    {
        $client = ServiceContainer::get(Client::class);

        try {
            $symbol = $this->getParam('symbol');
            $faction = $this->getParam('faction');

            if (!preg_match('/[[:alnum:]_]+/', $symbol)) {
                throw new \InvalidArgumentException("Symbol can only include letters, numbers, underscores");
            }

            if (!preg_match('/[[:alnum:]_]+/', $faction)) {
                throw new \InvalidArgumentException("Faction can only include letters, numbers, underscores");
            }

            $response = $client->register($symbol, $faction);
            $this->success(json_encode($response, flags: JSON_PRETTY_PRINT));
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }
}