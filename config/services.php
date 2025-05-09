<?php

use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\Psr6CacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use Phparch\SpaceTraders;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Symfony\Component\Cache\Adapter\RedisAdapter;

return [
    Predis\Client::class => function () {
        return new Predis\Client($_ENV['REDIS_URI']);
    },
    GuzzleHttp\Client::class => function () {
        $adapter = new RedisAdapter(
            redis: ServiceContainer::get(Predis\Client::class),
            namespace: '',
            defaultLifetime: $_ENV['REDIS_CACHE_TTL'] ?? 900,
        );

        $strategy = new GreedyCacheStrategy(
            new Psr6CacheStorage($adapter),
            $_ENV['GUZZLE_REQUEST_CACHE_TTL'] ?? 900,
        );
        $stack = GuzzleHttp\HandlerStack::create();
        $stack->push(new CacheMiddleware($strategy), 'cache');
        return new GuzzleHttp\Client(['handler' => $stack]);
    },
    SpaceTraders\RoutesMapper::class => function () {
        return new SpaceTraders\RoutesMapper(
            srcRootDir: dirname(__DIR__) . '/src/',
            controllerDirs: [
                [
                    'namespace' => 'Phparch\\SpaceTraders',
                    'path' => dirname(__DIR__) . '/src/Controller/'
                ]
            ],
            container: ServiceContainer::instance(),
        );
    }
];