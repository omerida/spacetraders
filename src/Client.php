<?php

namespace Phparch\SpaceTraders;

use Psr\Http\Message\ResponseInterface;

abstract class Client
{
    private string $baseURI = 'https://api.spacetraders.io/v2/';

    public function __construct(
        private string $token,
        private \GuzzleHttp\Client $guzzle,
    ) {
    }

    protected function get(string $url, bool $authenticate = true)
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];

        if ($authenticate) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        return $this->guzzle->get(
            $this->baseURI . $url,
            ['headers' => $headers]
        );
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    protected function post(
        string $url,
        array $data = [],
        bool $authenticate = true
    ): ResponseInterface {
        $headers = [
            'Content-Type' => 'application/json'
        ];
        if ($authenticate) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        $response = $this->guzzle->post(
            $this->baseURI . $url,
            [
                'headers' => $headers,
                'body' => $data ? json_encode($data) : null,
            ]
        );

        return $response;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    protected function patch(
        string $url,
        array $data = [],
        bool $authenticate = true
    ): ResponseInterface {
        $headers = [
            'Content-Type' => 'application/json'
        ];
        if ($authenticate) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        $response = $this->guzzle->patch(
            $this->baseURI . $url,
            [
                'headers' => $headers,
                'body' => $data ? json_encode($data) : null,
            ]
        );

        return $response;
    }

    /**
     * Returns the API response as a raw decoded object from JSON string
     * @return array
     * @throws \JsonException
     */
    protected function decodeResponse(
        \Psr\Http\Message\ResponseInterface $response
    ): array {
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    protected function convertResponse(
        \Psr\Http\Message\ResponseInterface $response,
        string $responseClass
    ) {
        $json = $this->decodeResponse($response);
        return $responseClass::fromArray($json['data']);
    }
}
