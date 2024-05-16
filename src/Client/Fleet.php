<?php

namespace Phparch\SpaceTraders\Client;

use Phparch\SpaceTraders\Client;
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
}

