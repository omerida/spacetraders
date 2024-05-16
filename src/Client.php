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

    /**
     * Returns the API response as a raw decoded object from JSON string
     * @param \GuzzleHttp\Psr7\Response $response
     * @return array
     * @throws \JsonException
     */
    protected function decodeResponse(\GuzzleHttp\Psr7\Response $response): array
    {
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    protected function convertResponse(\GuzzleHttp\Psr7\Response $response, string $responseClass)
    {
        $json = $this->decodeResponse($response);
        return $responseClass::fromArray($json['data']);
    }
}