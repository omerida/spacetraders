<?php

namespace Phparch\SpaceTraders\Controller;

use GuzzleHttp\Psr7\Response;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Response\Agent;
use Phparch\SpaceTraders\Response\Fleet\ListShips;
use Phparch\SpaceTraders\Value\Ship\FlightMode;
use Phparch\SpaceTraders\Value\WaypointType;
use Psr\Http\Message\ResponseInterface;

class IndexController
{
    public function __construct(
        private Client\Agents $client,
        private Client\Fleet $fleet,
    ) {
    }

    #[Route(name: 'homepage', path: '/', methods: ['GET'], strategy: 'application')]
    public function index(): ResponseInterface
    {
        $agent = $this->client->myAgent();
        $ships = $this->fleet->listShips();
        $response = new Response();
        $response->getBody()->write(
            $this->hello($agent, $ships),
        );
        return $response->withStatus(200);
    }

    private function hello(Agent $agent, ListShips $ships): string
    {

        $credits = number_format($agent->credits);

        $types = '';
        foreach (WaypointType::cases() as $type) {
            $types .= '<option value="' . $type->value . '">' . $type->name . '</option>';
        }

        $flightmodes = '';
        foreach(FlightMode::cases() as $mode) {
            $flightmodes .= '<option value="' . $mode->value . '">' . $mode->name . '</option>';
        }

        $ship_rows = "";
        $ship_opts = "";
        foreach ($ships->ships as $ship) {
            $ship_rows .= "<tr><td>{$ship->symbol}</td>";
            $ship_rows .= "<td>{$ship->nav->waypointSymbol}</td>";
            $ship_rows .= "<td>{$ship->nav->status->value}</td>";
            $ship_rows .= "<td>{$ship->nav->flightMode->value}</td>";
            $ship_rows .= "<td>{$ship->fuel->current} / {$ship->fuel->capacity}</td>";
            $ship_rows .= "<td>{$ship->cooldown->remainingSeconds}</td>";
            $ship_rows .= "<td><a href=\"/ship/info?ship={$ship->symbol}\" "
            . "target='_blank'>[view]</a></td>";
            $ship_rows .= "<td>{$ship->cargo->units} / {$ship->cargo->capacity} "
            . "<a href=\"/ship/cargo?ship={$ship->symbol}\" target='_blank'>[cargo]</a></td>";
            $ship_rows .= "</tr>";

            $ship_opts .= "<option value='{$ship->symbol}'>{$ship->symbol}</option>";
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

<table border="1">
<tr>
<th>Symbol</th>
<th>Waypoint</th>
<th>Status</th>
<th>Flightmode</th>
<th>Fuel</th>
<th>Cooldown Remaining</th>
<th></th>
<th></th>
</tr>
{$ship_rows}
</table>

<p><a href="/my/ships" target="_blank">[Your Ships]</a></p>

<h3>Ship Orders</h3>
<form action="/ship/orders" target="_blank" method="post">
    <select name="ship" required>
        <option disabled>--Select Ship--</option>
        {$ship_opts}
    </select>
    <select name="order" required>
        <option disabled>--Select Order--</option>
        <option value="orbit">Orbit</option>
        <option value="dock">Dock</option>
        <option value="extract">Extract</option>
        <option value="refuel">Refuel</option>
    </select>
    <input type="submit" value="Issue Order">
</form>

<h3>Navigate</h3>
<form action="/ship/navigate" target="_blank" method="post">
    <select name="ship" required>
        <option>--Select Ship--</option>
        {$ship_opts}
    </select>
    <input type="text" name="waypoint" placeholder="waypoint" required>
    <input type="submit" value="Navigate Ship">
</form>

<h3>Set Flight ModeOrders</h3>
<form action="/ship/set-flight-mode" target="_blank" method="post">
    <select name="ship" required>
        <option disabled>--Select Ship--</option>
        {$ship_opts}
    </select>
    <select name="flightmode" required>
        <option value="">--Select Flight Mode--</option>
        {$flightmodes}
    <input type="submit" value="Set Flight Mode">
</form>
HTML;
    }
}
