<?php

namespace Phparch\SpaceTraders\Value\Shipyard;

use Phparch\SpaceTraders\Value\Goods\SupplyLevel;
use Phparch\SpaceTraders\Value\Goods\TradeActivityLevel;
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
        public SupplyLevel $supply,
        public TradeActivityLevel $activity,
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
