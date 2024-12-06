<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;

class Ship extends Base
{
    public function __construct(
        public readonly string $symbol,
        public readonly Ship\Nav $nav,
        public readonly ShipCrew $crew,
        public readonly ShipFuel $fuel,
        public readonly ShipCoolDown $cooldown,
        public readonly ShipFrame $frame,
        public readonly ShipReactor $reactor,
        public readonly ShipEngine $engine,
        /** @var ShipModule[]  */
        public readonly array $modules,
        /** @var ShipMount[] */
        public readonly array $mounts,
        public readonly RegistrationInfo $registration,
        public readonly ShipCargoDetails $cargo,
    ) {
    }
}
