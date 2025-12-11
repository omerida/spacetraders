<?php

namespace Phparch\SpaceTraders\Client;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Phparch\SpaceTraders\APIException;
use Phparch\SpaceTraders\Response;

class Agents extends \Phparch\SpaceTraders\Client
{
    /**
     * @throws GuzzleException
     * @throws APIException
     * @throws \JsonException
     */
    public function myAgent(): \Phparch\SpaceTraders\Value\Agent
    {
        try {
            return $this->convertResponse(
                $this->get('my/agent'),
                \Phparch\SpaceTraders\Value\Agent::class
            );
        } catch (ClientException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }

    #[\Deprecated]
    public function register(string $symbol, string $faction): \Phparch\SpaceTraders\Value\Register
    {
        try {
            $response = $this->post(
                'register',
                data: [
                    'symbol' => $symbol,
                    'faction' => $faction
                ],
                authenticate: true
            );

            return $this->convertResponse(
                $response,
                \Phparch\SpaceTraders\Value\Register::class
            );
        } catch (ClientException $e) {
            $body = $e->getResponse()->getBody()->getContents();
            throw new APIException($body);
        }
    }
}
