<?php

namespace Phparch\SpaceTraders;

class Client
{
    private string $baseURI = 'https://api.spacetraders.io/v2/';

    public function __construct(
        private string $token,
        private \GuzzleHttp\Client $guzzle,
    ) {}

    public function register(string $symbol, string $faction)
    {
        return $this->post('register',
            data: [
                'symbol' => $symbol,
                'faction' => $faction
            ],
            authenticate: false);
    }

    public function myAgent(): object
    {
        $response = $this->get('my/agent');

        return $response;
    }

    private function get(string $url, bool $authenticate = true)
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

    private function post(string $url, array $data, bool $authenticate = true)
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

        return json_decode($response->getBody());
    }
}