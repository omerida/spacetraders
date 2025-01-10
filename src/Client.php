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

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function get(string $url, bool $authenticate = true): ResponseInterface
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
     * @param array<string, mixed> $data
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
     * @param array<string, mixed> $data
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
     * @return array<string, mixed>
     * @throws \JsonException
     */
    protected function decodeResponse(
        \Psr\Http\Message\ResponseInterface $response
    ): array {
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @todo Use an interface instead of an AbstractClass here?
     *
     * @template R of \Phparch\SpaceTraders\Response\Base
     * @param class-string<R> $responseClass
     * @return R
     * @throws \JsonException
     */
    protected function convertResponse(
        \Psr\Http\Message\ResponseInterface $response,
        string $responseClass
    ) {
        $json = $this->decodeResponse($response);
        return $responseClass::fromArray($json['data']);
    }
}
