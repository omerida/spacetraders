<?php

namespace Phparch\SpaceTraders\Client;

use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Response;

class Contracts extends Client
{
    public function myContracts(): object
    {
        $response = $this->get('my/contracts');
        $json = $this->decodeResponse($response);

        return Response\My\Contracts::fromArray($json['data']);
    }
}