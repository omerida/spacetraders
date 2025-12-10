<?php

use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\Psr6CacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use Phparch\SpaceTraders;
use Phparch\SpaceTraders\Routes;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\TwigExtensions;
use Symfony\Component\Cache\Adapter\RedisAdapter;

return [
    Predis\Client::class => static function () {
        return new Predis\Client($_ENV['REDIS_URI']);
    },
    GuzzleHttp\Client::class => static function () {
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
    Routes\Scanner::class => static function () {
        return new Routes\Scanner(
            controllerDirs: [
                [
                    'namespace' => 'Phparch\\SpaceTraders',
                    'path' => dirname(__DIR__) . '/src/Controller/'
                ]
            ],
            ref: ServiceContainer::get(
                \Roave\BetterReflection\BetterReflection::class
            ),
            useAPCu: $_ENV['USE_APCU'] === 1,
        );
    },
    Routes\Mapper::class => static function () {
        return new SpaceTraders\Routes\Mapper(
            scanner: ServiceContainer::get(Routes\Scanner::class),
            registry: ServiceContainer::get(Routes\Registry::class),
        );
    },
    Routes\Registry::class => static function () {
        return new Routes\Registry(
            container: ServiceContainer::instance(),
            router: ServiceContainer::get(League\Route\Router::class),
            decorator: ServiceContainer::get(Routes\Decorator::class)
        );

    },
    Twig\Environment::class => static function () {
        $twig = new Twig\Environment(
            new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates/'),
            [
                'debug' => $_ENV['TWIG_DEBUG'] ?? false,
                'cache' => dirname(__DIR__) . '/templates_cache/',
                'auto_reload' => $_ENV['TWIG_AUTORELOAD'] ?? false,
                'autoescape' => 'html'
            ]
        );
        if ($twig->isDebug()) {
            $twig->addExtension(new \Twig\Extension\DebugExtension());
        }
        $twig->addExtension(
            new \Twig\Extension\AttributeExtension(TwigExtensions::class)
        );
        return $twig;
    }
];