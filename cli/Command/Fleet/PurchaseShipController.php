<?php

namespace Phparch\SpaceTradersCLI\Command\Fleet;

use InvalidArgumentException;
use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Value\Waypoint;
use Phparch\SpaceTradersCLI\Command\HelpInfo;
use Phparch\SpaceTradersCLI\Render;

#[HelpInfo(
    description: "Purchase a type of ship from a shipyard at a waypoint.",
    params: ['waypoint symbol', 'ship type']
)]
class PurchaseShipController extends CommandController
{
    public function handle(): void
    {
        $client = ServiceContainer::get(Client\Fleet::class);

        $args = $this->getArgs();
        $waypoint = $args[3] ?? null;
        if (!$waypoint) {
            throw new InvalidArgumentException("Please specify waypoint as third parameter");
        }
        $waypoint = new Waypoint\Symbol($waypoint);

        $type = strtoupper($args[4]);
        if (!$type) {
            throw new InvalidArgumentException("Please specify type as fourth parameter");
        }

        /* @TODO validate type is one of the allowed values */
        $response = $client->purchaseShip($waypoint, $type);

        $ship = new Render\Ship($response->ship);
        echo $ship->output();

        $agent = new Render\Agent($response->agent);
        echo $agent->output();

        $txn = new Render\Transaction($response->transaction);
        echo $txn->output();
    }

    private function getMock() // @phpstan-ignore-line
    {
        /* @phpcs:ignore */
        return unserialize('O:48:"Phparch\SpaceTraders\Response\Fleet\PurchaseShip":3:{s:5:"agent";O:35:"Phparch\SpaceTraders\Response\Agent":6:{s:9:"accountId";s:25:"cm47z3rq4d6cis60c0gsagiue";s:6:"symbol";s:11:"PHP_ARCHIE2";s:12:"headquarters";s:9:"X1-J69-A1";s:7:"credits";i:65597;s:15:"startingFaction";s:6:"COSMIC";s:9:"shipCount";i:5;}s:4:"ship";O:31:"Phparch\SpaceTraders\Value\Ship":12:{s:6:"symbol";s:13:"PHP_ARCHIE2-5";s:3:"nav";O:30:"Phparch\SpaceTraders\Value\Nav":5:{s:12:"systemSymbol";O:39:"Phparch\SpaceTraders\Value\SystemSymbol":2:{s:6:"sector";s:2:"X1";s:6:"system";s:6:"X1-J69";}s:14:"waypointSymbol";O:41:"Phparch\SpaceTraders\Value\WaypointSymbol":3:{s:6:"sector";s:2:"X1";s:6:"system";s:6:"X1-J69";s:8:"waypoint";s:10:"X1-J69-H56";}s:5:"route";O:32:"Phparch\SpaceTraders\Value\Route":4:{s:11:"destination";O:42:"Phparch\SpaceTraders\Value\ShipDestination":5:{s:6:"symbol";s:10:"X1-J69-H56";s:4:"type";s:4:"MOON";s:12:"systemSymbol";O:39:"Phparch\SpaceTraders\Value\SystemSymbol":2:{s:6:"sector";s:2:"X1";s:6:"system";s:6:"X1-J69";}s:1:"x";i:35;s:1:"y";i:-30;}s:6:"origin";O:37:"Phparch\SpaceTraders\Value\ShipOrigin":5:{s:6:"symbol";s:10:"X1-J69-H56";s:4:"type";s:4:"MOON";s:12:"systemSymbol";O:39:"Phparch\SpaceTraders\Value\SystemSymbol":2:{s:6:"sector";s:2:"X1";s:6:"system";s:6:"X1-J69";}s:1:"x";i:35;s:1:"y";i:-30;}s:13:"departureTime";O:17:"DateTimeImmutable":3:{s:4:"date";s:26:"2024-12-03 04:59:47.387000";s:13:"timezone_type";i:2;s:8:"timezone";s:1:"Z";}s:7:"arrival";O:17:"DateTimeImmutable":3:{s:4:"date";s:26:"2024-12-03 04:59:47.387000";s:13:"timezone_type";i:2;s:8:"timezone";s:1:"Z";}}s:6:"status";s:6:"DOCKED";s:10:"flightMode";s:6:"CRUISE";}s:4:"crew";O:35:"Phparch\SpaceTraders\Value\ShipCrew":6:{s:7:"current";i:0;s:8:"capacity";i:0;s:8:"required";i:0;s:8:"rotation";s:6:"STRICT";s:6:"morale";i:100;s:5:"wages";i:0;}s:4:"fuel";O:35:"Phparch\SpaceTraders\Value\ShipFuel":3:{s:7:"current";i:80;s:8:"capacity";i:80;s:8:"consumed";O:39:"Phparch\SpaceTraders\Value\FuelConsumed":2:{s:6:"amount";i:0;s:9:"timestamp";O:17:"DateTimeImmutable":3:{s:4:"date";s:26:"2024-12-03 04:59:47.387000";s:13:"timezone_type";i:2;s:8:"timezone";s:1:"Z";}}}s:8:"cooldown";O:39:"Phparch\SpaceTraders\Value\ShipCoolDown":4:{s:10:"shipSymbol";s:13:"PHP_ARCHIE2-5";s:12:"totalSeconds";i:0;s:16:"remainingSeconds";i:0;s:10:"expiration";N;}s:5:"frame";O:36:"Phparch\SpaceTraders\Value\ShipFrame":9:{s:6:"symbol";s:11:"FRAME_DRONE";s:4:"name";s:5:"Drone";s:11:"description";s:101:"A small, unmanned spacecraft used for various tasks, such as surveillance, transportation, or combat.";s:9:"condition";d:1;s:9:"integrity";d:1;s:11:"moduleSlots";i:3;s:14:"mountingPoints";i:2;s:12:"fuelCapacity";i:80;s:12:"requirements";O:48:"Phparch\SpaceTraders\Value\ShipFrameRequirements":3:{s:5:"power";i:1;s:4:"crew";i:-4;s:5:"slots";N;}}s:7:"reactor";O:38:"Phparch\SpaceTraders\Value\ShipReactor":7:{s:6:"symbol";s:18:"REACTOR_CHEMICAL_I";s:4:"name";s:18:"Chemical Reactor I";s:11:"description";s:85:"A basic chemical power reactor, used to generate electricity from chemical reactions.";s:9:"condition";d:1;s:9:"integrity";d:1;s:11:"powerOutput";i:15;s:12:"requirements";O:50:"Phparch\SpaceTraders\Value\ShipReactorRequirements":3:{s:4:"crew";i:3;s:5:"power";N;s:5:"slots";N;}}s:6:"engine";O:37:"Phparch\SpaceTraders\Value\ShipEngine":7:{s:6:"symbol";s:22:"ENGINE_IMPULSE_DRIVE_I";s:4:"name";s:15:"Impulse Drive I";s:11:"description";s:85:"A basic low-energy propulsion system that generates thrust for interplanetary travel.";s:9:"condition";d:1;s:9:"integrity";d:1;s:5:"speed";i:3;s:12:"requirements";O:49:"Phparch\SpaceTraders\Value\ShipEngineRequirements":3:{s:5:"power";i:1;s:4:"crew";i:0;s:5:"slots";N;}}s:7:"modules";a:2:{i:0;O:37:"Phparch\SpaceTraders\Value\ShipModule":5:{s:6:"symbol";s:19:"MODULE_CARGO_HOLD_I";s:4:"name";s:10:"Cargo Hold";s:11:"description";s:48:"A module that increases a ship\'s cargo capacity.";s:12:"requirements";O:49:"Phparch\SpaceTraders\Value\ShipModuleRequirements":3:{s:4:"crew";i:0;s:5:"power";i:1;s:5:"slots";i:1;}s:8:"capacity";i:15;}i:1;O:37:"Phparch\SpaceTraders\Value\ShipModule":5:{s:6:"symbol";s:26:"MODULE_MINERAL_PROCESSOR_I";s:4:"name";s:17:"Mineral Processor";s:11:"description";s:148:"Crushes and processes extracted minerals and ores into their component parts, filters out impurities, and containerizes them into raw storage units.";s:12:"requirements";O:49:"Phparch\SpaceTraders\Value\ShipModuleRequirements":3:{s:4:"crew";i:0;s:5:"power";i:1;s:5:"slots";i:2;}s:8:"capacity";N;}}s:6:"mounts";a:1:{i:0;O:36:"Phparch\SpaceTraders\Value\ShipMount":6:{s:6:"symbol";s:20:"MOUNT_MINING_LASER_I";s:4:"name";s:14:"Mining Laser I";s:11:"description";s:106:"A basic mining laser that can be used to extract valuable minerals from asteroids and other space objects.";s:12:"requirements";O:48:"Phparch\SpaceTraders\Value\ShipMountRequirements":2:{s:4:"crew";i:1;s:5:"power";i:1;}s:8:"strength";i:3;s:8:"deposits";a:0:{}}}s:12:"registration";O:43:"Phparch\SpaceTraders\Value\RegistrationInfo":3:{s:4:"name";s:13:"PHP_ARCHIE2-5";s:13:"factionSymbol";O:40:"Phparch\SpaceTraders\Value\FactionSymbol":1:{s:7:"faction";s:6:"COSMIC";}s:4:"role";s:9:"EXCAVATOR";}s:5:"cargo";O:43:"Phparch\SpaceTraders\Value\ShipCargoDetails":3:{s:8:"capacity";i:15;s:5:"units";i:0;s:9:"inventory";a:0:{}}}s:11:"transaction";O:47:"Phparch\SpaceTraders\Value\Shipyard\Transaction":6:{s:14:"waypointSymbol";O:41:"Phparch\SpaceTraders\Value\WaypointSymbol":3:{s:6:"sector";s:2:"X1";s:6:"system";s:6:"X1-J69";s:8:"waypoint";s:10:"X1-J69-H56";}s:10:"shipSymbol";s:17:"SHIP_MINING_DRONE";s:8:"shipType";s:17:"SHIP_MINING_DRONE";s:5:"price";i:37411;s:11:"agentSymbol";s:11:"PHP_ARCHIE2";s:9:"timestamp";O:17:"DateTimeImmutable":3:{s:4:"date";s:26:"2024-12-03 04:59:47.450000";s:13:"timezone_type";i:2;s:8:"timezone";s:1:"Z";}}}');
    }
}
