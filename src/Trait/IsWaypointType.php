<?php

namespace Phparch\SpaceTraders\Trait;

use Phparch\SpaceTraders\Value\WaypointType;

trait IsWaypointType
{
    public function isArtificialGravityWell(): bool
    {
        return $this->type === WaypointType::ARTIFICIAL_GRAVITY_WELL;
    }

    public function isAsteroid(): bool
    {
        return $this->type === WaypointType::ASTEROID;
    }

    public function isAsteroidBase(): bool
    {
        return $this->type === WaypointType::ASTEROID_BASE;
    }

    public function isAsteroidField(): bool
    {
        return $this->type === WaypointType::ASTEROID_FIELD;
    }

    public function isDebrisField(): bool
    {
        return $this->type === WaypointType::DEBRIS_FIELD;
    }

    public function isEngineeredAsteroid(): bool
    {
        return $this->type === WaypointType::ENGINEERED_ASTEROID;
    }

    public function isFuelStation(): bool
    {
        return $this->type === WaypointType::FUEL_STATION;
    }

    public function isGasGiant(): bool
    {
        return $this->type === WaypointType::GAS_GIANT;
    }

    public function isGravityWell(): bool
    {
        return $this->type === WaypointType::GRAVITY_WELL;
    }

    public function isJumpGate(): bool
    {
        return $this->type === WaypointType::JUMP_GATE;
    }

    public function isMoon(): bool
    {
        return $this->type === WaypointType::MOON;
    }

    public function isNebula(): bool
    {
        return $this->type === WaypointType::NEBULA;
    }

    public function isOrbitalStation(): bool
    {
        return $this->type === WaypointType::ORBITAL_STATION;
    }

    public function isPlanet(): bool
    {
        return $this->type === WaypointType::PLANET;
    }
}
