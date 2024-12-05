<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Response\Fleet\RefuelShip;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(
    description: "Refuel ship.",
    params: ['ship symbol']
)]
class RefuelShipController extends CommandController
{
    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $args = $this->getArgs();
        $shipSymbol = $args[3] ?? null;
        if (!$shipSymbol) {
            throw new \InvalidArgumentException("Please specify ship symbol as third parameter");
        }

        // TODO: allow users to specify how much to refuel
        // TODO: allow users to specify if refueling from cargo
        $response = $client->refuelShip($shipSymbol);

//        $response = $this->getMock();
        $agent = new Render\Agent($response->agent);
        echo $agent->output();
        $fuel = new Render\Ship\Fuel($response->fuel);
        echo $fuel->output();
        $txn = new Render\Ship\RefuelTransaction($response->transaction);
        echo $txn->output();
    }

    private function getMock() // @phpstan-ignore-line
    {
        /* @phpcs:ignore */
        $json = '{"data":{"agent":{"accountId":"XXXX","symbol":"PHP_ARCHIE2","headquarters":"X1-J69-A1","credits":65525,"startingFaction":"COSMIC","shipCount":5},"fuel":{"current":80,"capacity":80,"consumed":{"amount":20,"timestamp":"2024-12-04T04:11:22.693Z"}},"transaction":{"waypointSymbol":"X1-J69-AD5D","shipSymbol":"PHP_ARCHIE2-5","tradeSymbol":"FUEL","type":"PURCHASE","units":1,"pricePerUnit":72,"totalPrice":72,"timestamp":"2024-12-05T05:49:11.999Z"}}}';
        $response = json_decode($json, true);

        return RefuelShip::fromArray($response['data']);
    }
}
