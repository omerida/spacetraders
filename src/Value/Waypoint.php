<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Trait\MapFromArray;

class Waypoint
{
    use MapFromArray;

    public function __construct(
        public System\Symbol $systemSymbol,
        public Waypoint\Symbol $symbol,
        /** @var non-empty-string */
        public readonly string $type, // enum?
        public readonly int $x,
        public readonly int $y,
        /** @var list<\Phparch\SpaceTraders\Value\Orbital> */
        public readonly array $orbitals,
        /** @var list<\Phparch\SpaceTraders\Value\System\SystemTrait> */
        public readonly array $traits,
        /** @var list<\Phparch\SpaceTraders\Value\System\SystemTrait> */
        public readonly array $modifiers,
        public readonly System\Chart $chart,
        public readonly Waypoint\Faction $faction,
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
