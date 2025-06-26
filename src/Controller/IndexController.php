<?php

namespace Phparch\SpaceTraders\Controller;

use GuzzleHttp\Psr7\Response;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Response\Agent;
use Psr\Http\Message\ResponseInterface;

class IndexController {

    public function __construct(
        private Client\Agents $client,
    ) {
    }

    #[Route(name: 'homepage', path: '/', methods: ['GET'], strategy: 'application')]
    public function index(): ResponseInterface
    {
        $agent = $this->client->myAgent();

        $response = new Response;
        $response->getBody()->write($this->hello($agent));
        return $response->withStatus(200);
    }

    private function hello(Agent $agent): string {

        $credits = number_format($agent->credits);
        return <<<HTML
<h1>Welcome, Captain</h1>

<h2>Your Agent</h2>
<table border="1">
<tr>
    <th>Symbol</th>
    <th>HQ</th>
    <th>Credits</th>
    <th>Faction</th>
    <th>ShipCount</th>
</tr>
<tr>
    <td>{$agent->symbol}</td>
    <td>{$agent->headquarters}</td>
    <td>{$credits}</td>
    <td>{$agent->startingFaction}</td>
    <td>{$agent->shipCount}</td>
</tr>
</table>

<h2>Waypoints</h2>

<p>Quickstart suggests viewing your starting location</p>
<h3>View a Waypoint</h3>
<form method="get" action="/systems/waypoint" target="_blank">
    <input type="text" placeholder="waypoint symbol" name="id" required="required">
    <input type="submit" value="Scan">
</form>

<h2>Contracts</h2>

<p><a href="/contracts/" target="_blank">[Available Contracts]</a></p>
<form method="post" action="/contract/accept" target="_blank">
    <input type="text" name="id" placeholder="contract id" size="30" required="required">
    <input type="submit" value="Accept a Contract">
</form>
HTML;
    }
}