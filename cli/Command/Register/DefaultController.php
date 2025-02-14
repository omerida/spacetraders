<?php

namespace Phparch\SpaceTradersCLI\Command\Register;

use InvalidArgumentException;
use Minicli\Command\CommandController;
use Phparch\SpaceTraders\APIException;
use Phparch\SpaceTraders\Client\Agents;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(description: "Register a new account")]
class DefaultController extends CommandController
{
    public function required(): array
    {
        return ['symbol', 'faction'];
    }

    public function handle(): void
    {
        $client = ServiceContainer::get(Agents::class);

        try {
            $symbol = $this->getParam('symbol');
            $faction = $this->getParam('faction');

            if (!$symbol) {
                throw new InvalidArgumentException("Please specify symbol: symbol=");
            }

            if (!$faction) {
                throw new InvalidArgumentException("Please specify faction to join: faction=");
            }

            if (!preg_match('/[[:alnum:]_]+/', $symbol)) {
                throw new InvalidArgumentException(
                    "Symbol can only include letters, numbers, underscores"
                );
            }

            if (!preg_match('/[[:alnum:]_]+/', $faction)) {
                throw new InvalidArgumentException(
                    "Faction can only include letters, numbers, underscores"
                );
            }

            $response = $client->register($symbol, $faction);
            $r = new Render\Agent($response->agent);
            echo $r->output();
            echo PHP_EOL . $response->token;
        } catch (APIException $ex) {
            $error = new Render\Exception($ex);
            echo $error->output();
        }
    }
}
