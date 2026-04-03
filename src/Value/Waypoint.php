<?php

namespace Phparch\SpaceTraders\Value;

use Phparch\SpaceTraders\Trait\MapFromArray;

class Waypoint
{
    use MapFromArray;

    public function __construct(
        public System\Symbol $systemSymbol,
        public Waypoint\Symbol $symbol,
        public Waypoint\Type $type {
            set(string | Waypoint\Type $value) {
                if ($value instanceof Waypoint\Type) {
                    $this->type = $value;
                    return;
                }

                if (empty(trim($value))) {
                    throw new \InvalidArgumentException('type cannot be empty');
                }

                if ($enum = Waypoint\Type::tryFrom($value)) {
                    $this->type = $enum;
                } else {
                    throw new \InvalidArgumentException('unrecognized waypoint type');
                }
            }
        },
        public readonly int $x,
        public readonly int $y,
        /** @var list<\Phparch\SpaceTradersRest\Value\Orbital> */
        public readonly array $orbitals,
        /** @var list<\Phparch\SpaceTradersRest\Value\System\SystemTrait> */
        public readonly array $traits,
        /** @var list<\Phparch\SpaceTradersRest\Value\System\SystemTrait> */
        public readonly array $modifiers,
        public readonly System\Chart $chart,
        public readonly Waypoint\Faction $faction,
        public readonly bool $isUnderConstruction,
        public readonly string $orbits = "",
    ) {
    }

    private function hasSystemTrait(string $symbol): bool
    {
        return array_any($this->traits, fn($trait) => $trait->symbol === $symbol);
    }


    public function hasMarket(): bool
    {
        return $this->hasSystemTrait('MARKETPLACE');
    }
}
