<?php

namespace Phparch\SpaceTraders\Response\Fleet;

use Phparch\SpaceTraders\Trait\MapFromArray;
use Phparch\SpaceTraders\Value\Ship\CargoDetails;
use Phparch\SpaceTraders\Value\Ship\CoolDown;
use Phparch\SpaceTraders\Value\Ship\Extraction\Details;

class ExtractResources
{
    use MapFromArray;

    public function __construct(
        public readonly CoolDown $cooldown,
        public readonly CargoDetails $cargo,
        public readonly Details $extraction,
        /** @var array<array<string, string>> */
        public readonly array $events,
        /** @var array<array<string, string>> */
        public readonly array $modifiers,
    ) {
    }
}
