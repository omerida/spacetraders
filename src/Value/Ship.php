<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\Ship\CargoDetails;
use Phparch\SpaceTraders\Value\Ship\CoolDown;
use Phparch\SpaceTraders\Value\Ship\Crew\Details;
use Phparch\SpaceTraders\Value\Ship\Engine;
use Phparch\SpaceTraders\Value\Ship\Frame;
use Phparch\SpaceTraders\Value\Ship\Fuel;
use Phparch\SpaceTraders\Value\Ship\Module;
use Phparch\SpaceTraders\Value\Ship\Mount;
use Phparch\SpaceTraders\Value\Ship\Reactor;

class Ship extends Base
{
    public function __construct(
        public readonly string $symbol,
        public readonly Ship\Nav $nav,
        public readonly Details $crew,
        public readonly Fuel $fuel,
        public readonly CoolDown $cooldown,
        public readonly Frame $frame,
        public readonly Reactor $reactor,
        public readonly Engine $engine,
        /** @var Module[]  */
        public readonly array $modules,
        /** @var Mount[] */
        public readonly array $mounts,
        public readonly RegistrationInfo $registration,
        public readonly CargoDetails $cargo,
    ) {
    }
}
