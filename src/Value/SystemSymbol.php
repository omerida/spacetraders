<?php

namespace Phparch\SpaceTraders\Value;

class SystemSymbol
{
    public readonly string $sector;
    public readonly string $system;

    /** @see https://github.com/SpaceTradersAPI/api-docs/blob/main/models/SystemSymbol.json */
    public function __construct(string $input)
    {
        preg_match('/^([[:alnum:]]{1,2})\-([[:alnum:]]{1,5})$/', $input, $match);

        if (!$match) {
            throw new \InvalidArgumentException("Malformed system string");
        }

        $this->sector = $match[1];
        $this->system = $match[1] . '-' . $match[2];
    }

    public function __toString(): string
    {
        return $this->system;
    }
}
