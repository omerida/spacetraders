<?php

namespace Phparch\SpaceTraders;

abstract class Client
{
    private string $baseURI = 'https://api.spacetraders.io/v2/';

    public function __construct(
        private string $token,
        private \GuzzleHttp\Client $guzzle,
    ) {}

    protected function get(string $url, bool $authenticate = true)
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];

        if ($authenticate) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        return $this->guzzle->get(
            $this->baseURI . $url, [
            'headers' => $headers
        ]);
    }

    protected function post(string $url, array $data, bool $authenticate = true)
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];
        if ($authenticate) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        $response = $this->guzzle->post(
             $this->baseURI . $url, [
                'headers' => $headers,
                'body' => json_encode($data)
            ]
        );

        return $this->decodeResponse($response);
    }

    protected function decodeResponse(\GuzzleHttp\Psr7\Response $response): array
    {
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
}