<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use InvalidArgumentException;
use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\APIException;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(
    description: "Sell cargo at a marketplace waypoint.",
    params: ['ship symbol', 'cargo_symbol', 'no. units']
)]
class SellCargoController extends CommandController
{
    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $args = $this->getArgs();
        $shipSymbol = $args[3] ?? null;
        if (!$shipSymbol) {
            throw new InvalidArgumentException("Please specify ship symbol as third parameter");
        }

        $args = $this->getArgs();
        $cargoSymbol = $args[4] ?? null;
        if (!$cargoSymbol) {
            throw new InvalidArgumentException("Please specify cargo symbol as fourth parameter");
        }

        $args = $this->getArgs();
        $units = (int) ($args[5] ?? null);
        if (!$units) {
            throw new InvalidArgumentException(
                "Please specify how many units to sell as fifth parameter"
            );
        }

        try {
            $response = $client->sellCargo($shipSymbol, $cargoSymbol, $units);

            $agent = new Render\Agent($response->agent);
            echo $agent->output();

            $out = new Render\Ship\CargoDetails($response->cargo);
            echo $out->output();

            $out = new Render\Market\Transaction($response->transaction);
            echo $out->output();
        } catch (APIException $ex) {
            $error = new Render\Exception($ex);
            echo $error->output();
        }
    }
}
