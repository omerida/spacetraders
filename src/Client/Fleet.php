<?php

namespace Phparch\SpaceTraders\Client;

use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Value\Ship;
use Phparch\SpaceTraders\Response\Fleet\ListShips;
/**
 * For endpoints under "fleet" group.
 * <https://spacetraders.stoplight.io/docs/spacetraders/>
 */
class Fleet extends Client
{
    public function ListShips()
    {
        return $this->convertResponse(
            $this->get('my/ships'),
            ListShips::class
        );
    }

    public function getShip(string $ship)
    {
        return $this->convertResponse(
            $this->get('my/ships/' . $ship),
            Ship::class
        );
    }
}

