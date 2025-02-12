<?php

namespace Phparch\SpaceTraders\Value\Shipyard;

use Phparch\SpaceTraders\Value\GoodsActivityLevel;
use Phparch\SpaceTraders\Value\GoodsSupplyLevel;
use Phparch\SpaceTraders\Value\Ship as Ships;
use Phparch\SpaceTraders\Value\ShipModule;
use Phparch\SpaceTraders\Value\ShipMount;
use Phparch\SpaceTraders\Value\Shipyard\Ship\Crew;

class Ship
{
    public function __construct(
        public Ships\Type $type,
        public string $name,
        public string $description,
        public GoodsSupplyLevel $supply,
        public GoodsActivityLevel $activity,
        public int $purchasePrice,
        public Ship\Frame $frame,
        public Ship\Reactor $reactor,
        public Ship\Engine $engine,
        /** @var ShipModule[] */
        public array $modules,
        /** @var ShipMount[] */
        public array $mounts,
        public Crew $crew,
    ) {
    }
}
