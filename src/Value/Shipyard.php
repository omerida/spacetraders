<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\ShipTypes;

class Shipyard extends Base
{
    public function __construct(
        public WaypointSymbol $symbol,
        public int $modificationsFee,
        /** @var Shipyard\ShipType[] */
        public array $shipTypes,
        /** @var Shipyard\Transaction[] */
        public array $transactions = [],
        /** @var Shipyard\Ship[] */
        public array $ships = [],
    ) {
    }
}
