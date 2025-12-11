<?php

namespace Phparch\SpaceTraders;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Base class for making API calls to groups of endpoints.
 *
 * Any child class is automatically discovered and registered
 * by the Service Container class as long as it is in the
 * Client namespaces.
 */
abstract class Client
{
    private string $baseURI = 'https://api.spacetraders.io/v2/';

    final public function __construct(
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

    public function getAllPages(
        string $url,
        int $limit = 10,
        bool $authenticate = true
    ): ResponseInterface {
        $headers = [
            'Content-Type' => 'application/json'
        ];

        if ($authenticate) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        $keepFetching = true;
        $iter = 0;
        $data = [];
        $max = null;
        $page = 1;
        while ($keepFetching && $iter < 100) {
            // "rebuild" the params
            $parts = parse_url($url);

            if (!isset($parts['path'])) {
                throw new \InvalidArgumentException("URL path is missing");
            }

            if (isset($parts['query'])) {
                parse_str($parts['query'], $parts['query']);
            } else {
                $parts['query'] = [];
            }

            $parts['query']['limit'] = $limit;
            $parts['query']['page'] = $page;

            $currUrl = $parts['path'] . '?' . http_build_query($parts['query']);
            $response = $this->guzzle->get(
                $this->baseURI . $currUrl,
                ['headers' => $headers]
            );

            /**
             * @var array{data: array<mixed>, meta: array{total: int}} $json
             */
            $json = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

            if (!$max) {
                $max = $json['meta']['total'];
            }

            $data = array_merge($data, $json['data']);

            if (count($data) >= $max) {
                $keepFetching = false;
            }

            $iter++;
            $page++;
        }

        // Amalgamate our responses as if it was one data key
        $raw = [
            'data' => $data,
        ];

        $response = new Response();
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(
            json_encode($raw, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT)
        );
        return $response;
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

        $headers = [];
        if ($data) {
            $headers = [
                'Content-Type' => 'application/json'
            ];
        }

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

        return $this->guzzle->patch(
            $this->baseURI . $url,
            [
                'headers' => $headers,
                'body' => $data ? json_encode($data) : null,
            ]
        );
    }

    /**
     * @todo Use an interface instead of an AbstractClass here?
     *
     * @template T of object
     * @param class-string<T> $responseClass
     * @return T
     * @throws \JsonException
     */
    protected function convertResponse(
        \Psr\Http\Message\ResponseInterface $response,
        string $responseClass
    ) {
        $json = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if ($json) {
            // If we can parse a response, it'll hava a data key.
            /** @phpstan-ignore-next-line */
            return $responseClass::fromArray($json['data']);
        }

        throw new \RuntimeException("Could not parse JSON Response");
    }
}
