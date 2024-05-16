<?php

namespace Phparch\SpaceTraders\Client;

use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Response;

class Contracts extends Client
{
    public function myContracts(): object
    {
        return $this->convertResponse(
            $this->get('my/contracts'),
            Response\Contracts\Contracts::class
        );
    }
}