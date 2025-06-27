<?php

namespace Phparch\SpaceTraders\Controller;

use GuzzleHttp\Psr7\Response;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Response\Agent;
use Phparch\SpaceTraders\Value\WaypointType;
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

        $types = '';
        foreach (WaypointType::cases() as $type) {
            $types .= '<option value="' . $type->value . '">' . $type->name . '</option>';
        }


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
    <input type="text"
           placeholder="waypoint symbol" 
           name="id" 
           required="required">
    <input type="submit" value="Scan">
</form>
<h3>Search For Waypoints</h3>
<p>Trait can be a string like <em>Shipyard</em></p>
<form method="get" action="/systems/waypoint/search" target="_blank">
    <input type="text" value="" name="system" required placeholder="System">
    <input type="text"
           placeholder="traits" 
           name="traits" >
    <select name="type">
        <option value="">Any type</option>
        {$types}
    </select>
    <input type="submit" value="Search">
</form>

<h3>View Shipyard</h3>
<form method="get" action="/systems/waypoint/shipyard" target="_blank">

    <input type="text" value="" name="id" 
           placeholder="waypoint symbol" required>
   <input type="submit" value="View Shipyard">
</form>

<h3>View Market</h3>
<form method="get" action="/systems/waypoint/market" target="_blank">

    <input type="text" value="" name="id" 
           placeholder="waypoint symbol" required>
   <input type="submit" value="View Market">
</form>
<h2>Contracts</h2>

<p><a href="/contracts/" target="_blank">[Available Contracts]</a></p>
<form method="post" action="/contract/accept" target="_blank">
    <input type="text" name="id" placeholder="contract id" 
           autocomplete="off" size="30" required="required">
    <input type="submit" value="Accept a Contract">
</form>

<h2>Ships</h2>
HTML;
    }
}