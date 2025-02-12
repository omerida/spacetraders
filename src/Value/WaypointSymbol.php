<?php

namespace Phparch\SpaceTraders\Value;

class WaypointSymbol
{
    public readonly string $sector;
    public readonly string $system;
    public readonly string $waypoint;

    /** @see https://github.com/SpaceTradersAPI/api-docs/blob/main/models/WaypointSymbol.json */
    public function __construct(string $input)
    {
        preg_match(
            '/^([[:alnum:]]{1,2})\-([[:alnum:]]{1,5})\-([[:alnum:]]{1,5})$/',
            $input,
            $match
        );

        if (!$match) {
            throw new \InvalidArgumentException("Malformed waypoint string");
        }

        $this->sector = $match[1];
        $this->system = $match[1] . '-' . $match[2];
        $this->waypoint = $match[1] . '-' . $match[2] . '-' . $match[3];
    }

    public function __toString(): string
    {
        return $this->waypoint;
    }
}
