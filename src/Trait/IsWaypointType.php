<?php

namespace Phparch\SpaceTraders\Trait;

use Phparch\SpaceTraders\Value\Waypoint\Type;

trait IsWaypointType
{
    public function isArtificialGravityWell(): bool
    {
        return $this->type === Type::ARTIFICIAL_GRAVITY_WELL;
    }

    public function isAsteroid(): bool
    {
        return $this->type === Type::ASTEROID;
    }

    public function isAsteroidBase(): bool
    {
        return $this->type === Type::ASTEROID_BASE;
    }

    public function isAsteroidField(): bool
    {
        return $this->type === Type::ASTEROID_FIELD;
    }

    public function isDebrisField(): bool
    {
        return $this->type === Type::DEBRIS_FIELD;
    }

    public function isEngineeredAsteroid(): bool
    {
        return $this->type === Type::ENGINEERED_ASTEROID;
    }

    public function isFuelStation(): bool
    {
        return $this->type === Type::FUEL_STATION;
    }

    public function isGasGiant(): bool
    {
        return $this->type === Type::GAS_GIANT;
    }

    public function isGravityWell(): bool
    {
        return $this->type === Type::GRAVITY_WELL;
    }

    public function isJumpGate(): bool
    {
        return $this->type === Type::JUMP_GATE;
    }

    public function isMoon(): bool
    {
        return $this->type === Type::MOON;
    }

    public function isNebula(): bool
    {
        return $this->type === Type::NEBULA;
    }

    public function isOrbitalStation(): bool
    {
        return $this->type === Type::ORBITAL_STATION;
    }

    public function isPlanet(): bool
    {
        return $this->type === Type::PLANET;
    }
}
