<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Trait\MapFromArray;

class Ship
{
    use MapFromArray;

    public function __construct(
        public readonly string $symbol,
        public readonly Ship\Nav $nav,
        public readonly Ship\Crew\Details $crew,
        public readonly Ship\Fuel $fuel,
        public readonly Ship\CoolDown $cooldown,
        public readonly Ship\Frame $frame,
        public readonly Ship\Reactor $reactor,
        public readonly Ship\Engine $engine,
        /** @var Ship\Module[]  */
        public readonly array $modules,
        /** @var Ship\Mount[] */
        public readonly array $mounts,
        public readonly RegistrationInfo $registration,
        public readonly Ship\CargoDetails $cargo,
    ) {
    }
}
