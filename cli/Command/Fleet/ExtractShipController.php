<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\SpaceTradersException;
use Phparch\SpaceTraders\Value;
use Phparch\SpaceTraders\Response\Fleet\ExtractResources;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(
    description: "Extract mininerals at a waypoint.",
    params: ['ship symbol']
)]
class ExtractShipController extends CommandController
{
    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $args = $this->getArgs();
        $shipSymbol = $args[3] ?? null;
        if (!$shipSymbol) {
            throw new \InvalidArgumentException("Please specify ship symbol as third parameter");
        }

        try {
            $response = $client->extractShip($shipSymbol);
            //$response = $this->getMock();

            $cooldown = new Render\Ship\Cooldown($response->cooldown);
            echo $cooldown->output();
            $cargo = new Render\Ship\CargoDetails($response->cargo);
            echo $cargo->output();
            $extraction = new Render\Ship\ExtractionDetails($response->extraction);
            echo $extraction->output();
            // TODO render events
        } catch (SpaceTradersException $ex) {
            $error = new Render\Exception($ex);
            echo $error->output();
            $cooldown = Value\ShipCoolDown::fromArray($ex->data['cooldown']);
            $render = new Render\Ship\Cooldown($cooldown);
            echo $render->output();
        }
    }

    public function getMock(): ExtractResources
    {
        /* @phpcs:ignore */
        $json = '{
  "data": {
    "cooldown": {
      "shipSymbol": "string",
      "totalSeconds": 0,
      "remainingSeconds": 0,
      "expiration": "2019-08-24T14:15:22Z"
    },
    "extraction": {
      "shipSymbol": "string",
      "yield": {
        "symbol": "PRECIOUS_STONES",
        "units": 0
      }
    },
    "cargo": {
      "capacity": 0,
      "units": 0,
      "inventory": [
        {
          "symbol": "PRECIOUS_STONES",
          "name": "string",
          "description": "string",
          "units": 1
        }
      ]
    },
    "events": [
      {
        "symbol": "REACTOR_OVERLOAD",
        "component": "FRAME",
        "name": "string",
        "description": "string"
      }
    ]
  }}';
        $response = json_decode($json, true);
        return ExtractResources::fromArray($response['data']);
    }
}
