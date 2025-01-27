<?php

namespace Phparch\SpaceTraders\Client;

use GuzzleHttp\Exception\ClientException;
use Phparch\SpaceTraders\APIException;
use Phparch\SpaceTraders\Response;

class Agents extends \Phparch\SpaceTraders\Client
{
    public function myAgent(): Response\Agent
    {
        return $this->convertResponse(
            $this->get('my/agent'),
            Response\Agent::class
        );
    }

    public function register(string $symbol, string $faction): Response\Register
    {
        try {
            $response = $this->post(
                'register',
                data: [
                    'symbol' => $symbol,
                    'faction' => $faction
                ],
                authenticate: false
            );

            return $this->convertResponse(
                $response,
                Response\Register::class
            );
        } catch (ClientException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }
}
