<?php

namespace Phparch\SpaceTraders\Value\Shipyard;

use Phparch\SpaceTraders\Value\ShipModule;
use Phparch\SpaceTraders\Value\ShipMount;
use Phparch\SpaceTraders\Value\Shipyard\Ship\Crew;

class Ship
{
    public function __construct(
        public string $type, // enum
        public string $name,
        public string $description,
        public string $supply, // enum
        public int $purchasePrice,
        public Ship\Frame $frame,
        public Ship\Reactor $reactor,
        public Ship\Engine $engine,
        /** @var ShipModule[] */
        public array $modules,
        /** @var ShipMount[] */
        public array $mounts,
        public Crew $crew,
        public string $activity, // enum
    ) {
    }
}
