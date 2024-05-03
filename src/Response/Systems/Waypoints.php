<?php

namespace Phparch\SpaceTraders\Response\Systems;

use Phparch\SpaceTraders\Response\Base;

class Waypoints extends Base
{
    public function __construct(
        /** @var non-empty-string */
        public readonly string $systemSymbol,
        /** @var non-empty-string */
        public readonly string $symbol,
        /** @var non-empty-string */
        public readonly string $type,
        public readonly int $x,
        public readonly int $y,
        /** @var list<\Phparch\SpaceTraders\Value\Orbital> */
        public readonly array $orbitals,
        /** @var list<\Phparch\SpaceTraders\Value\SystemTrait> */
        public readonly array $traits,
        /** @var list<\Phparch\SpaceTraders\Value\SystemTrait> */
        public readonly array $modifiers,
        /** @var array{submittedBy: string, submittedOn: string } */
        public readonly array $chart,
        /** @var array{symbol: string } */
        public readonly array $faction,
        public readonly bool $isUnderConstruction
    ) {}
}