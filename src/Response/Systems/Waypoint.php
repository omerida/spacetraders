<?php

namespace Phparch\SpaceTraders\Response\Systems;

use Phparch\SpaceTraders\Response\Base;
use Phparch\SpaceTraders\Value\SystemSymbol;
use Phparch\SpaceTraders\Value\Waypoint\Faction;
use Phparch\SpaceTraders\Value\Waypoint\Symbol;

class Waypoint extends Base
{
    public function __construct(
        public SystemSymbol $systemSymbol,
        public Symbol $symbol,
        /** @var non-empty-string */
        public readonly string $type, // enum?
        public readonly int $x,
        public readonly int $y,
        /** @var list<\Phparch\SpaceTraders\Value\Orbital> */
        public readonly array $orbitals,
        /** @var list<\Phparch\SpaceTraders\Value\SystemTrait> */
        public readonly array $traits,
        /** @var list<\Phparch\SpaceTraders\Value\SystemTrait> */
        public readonly array $modifiers,
        public readonly \Phparch\SpaceTraders\Value\SystemChart $chart,
        public readonly Faction $faction,
        public readonly bool $isUnderConstruction,
        public readonly string $orbits = "",
    ) {
    }

    private function hasSystemTrait(string $symbol): bool
    {
        foreach ($this->traits as $trait) {
            if ($trait->symbol === $symbol) {
                return true;
            }
        }

        return false;
    }
    public function hasMarket(): bool
    {
        return $this->hasSystemTrait('MARKETPLACE');
    }
}
