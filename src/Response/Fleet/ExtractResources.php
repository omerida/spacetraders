<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Ship\ExtractionDetails;
use Phparch\SpaceTraders\Value\ShipCargoDetails;
use Phparch\SpaceTraders\Value\ShipCoolDown;

class ExtractResources extends Base
{
    public function __construct(
        public readonly ShipCoolDown $cooldown,
        public readonly ShipCargoDetails $cargo,
        public readonly ExtractionDetails $extraction,
        /** @var array<array<string, string>> */
        public readonly array $events,
        /** @var array<array<string, string>> */
        public readonly array $modifiers,
    ) {
    }
}
