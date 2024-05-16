<?php

namespace Phparch\SpaceTraders\Client;

use Phparch\SpaceTraders\Response;

class Agents extends \Phparch\SpaceTraders\Client
{
    public function myAgent(): object {
        $response = $this->get('my/agent');
        $json = $this->decodeResponse($response);

        return Response\Agent::fromArray($json['data']);
    }

    public function register(string $symbol, string $faction)
    {
        return $this->post('register',
            data: [
                'symbol' => $symbol,
                'faction' => $faction
            ],
            authenticate: false);
    }
}