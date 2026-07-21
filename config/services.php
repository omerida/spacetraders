<?php

use Doctrine\DBAL;
use GuzzleHttp\Psr7;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\Psr6CacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use League\Route;
use Phparch\SpaceTraders;
use Phparch\SpaceTraders\Data;
use Phparch\SpaceTraders\Middleware;
use Phparch\SpaceTraders\Routes;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\TwigExtensions;
use Phparch\SpaceTradersRest\Client;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;

function getSpaceTradersToken(): string {
    $registry = ServiceContainer::get(Data\SystemRegistry::class);
    return $registry->getString('spacetraders_token') ?? '';
}

return [
    Client\Agents::class => static function(): Client\Agents {
        return new Client\Agents(
            getSpaceTradersToken(),
            ServiceContainer::get(\GuzzleHttp\Client::class),
            ServiceContainer::get(EventDispatcherInterface::class),
        );
    },
    Client\Contracts::class => static function(): Client\Contracts {
        return new Client\Contracts(
            getSpaceTradersToken(),
            ServiceContainer::get(\GuzzleHttp\Client::class),
            ServiceContainer::get(EventDispatcherInterface::class),
        );
    },
    Client\Fleet::class => static function(): Client\Fleet {
        return new Client\Fleet(
            getSpaceTradersToken(),
            ServiceContainer::get(\GuzzleHttp\Client::class),
            ServiceContainer::get(EventDispatcherInterface::class),
        );
    },
    Client\ShipActions::class => static function(): Client\ShipActions {
        return new Client\ShipActions(
            getSpaceTradersToken(),
            ServiceContainer::get(\GuzzleHttp\Client::class),
            ServiceContainer::get(EventDispatcherInterface::class),
        );
    },
    Client\ShipTravel::class => static function(): Client\ShipTravel {
        return new Client\ShipTravel(
            getSpaceTradersToken(),
            ServiceContainer::get(\GuzzleHttp\Client::class),
            ServiceContainer::get(EventDispatcherInterface::class),
        );
    },
    Client\Systems::class => static function() {
        return new Client\Systems(
            getSpaceTradersToken(),
            ServiceContainer::get(\GuzzleHttp\Client::class),
            ServiceContainer::get(EventDispatcherInterface::class),
        );
    },
    DBAL\Connection::class => static function(): DBAL\Connection {
        if (!isset($_ENV['DATABASE_DSN'])) {
            throw new \RuntimeException('Database DSN is not defined');
        }
        assert(is_string($_ENV['DATABASE_DSN']));
        $dsn = $_ENV['DATABASE_DSN'];
        $dsnParser = new DBAL\Tools\DsnParser();
        return DBAL\DriverManager::getConnection($dsnParser->parse($dsn));
    },
    Data\SystemRegistry::class => static function(): Data\SystemRegistry {
        return new Data\SystemRegistry(
            ServiceContainer::get(DBAL\Connection::class),
        );
    },
    EventDispatcherInterface::class => static function(): EventDispatcherInterface {
        return new Crell\Tukio\Dispatcher(
            ServiceContainer::get(ListenerProviderInterface::class),
        );
    },
    Predis\Client::class => static function () {
        return new Predis\Client($_ENV['REDIS_URI']);
    },
    GuzzleHttp\Client::class => static function () {
        if (
            isset($_ENV['REDIS_CACHE_TTL'])
            && ctype_digit($_ENV['REDIS_CACHE_TTL'])
        ) {
            $ttl = (int) $_ENV['REDIS_CACHE_TTL'];
        } else {
            $ttl = 900;
        }

        $adapter = new RedisAdapter(
            redis: ServiceContainer::get(Predis\Client::class),
            namespace: '',
            defaultLifetime: $ttl,
        );

        $strategy = new GreedyCacheStrategy(
            new Psr6CacheStorage($adapter),
            $_ENV['GUZZLE_REQUEST_CACHE_TTL'] ?? 900,
        );
        $stack = GuzzleHttp\HandlerStack::create();
        $stack->push(new CacheMiddleware($strategy), 'cache');
        return new GuzzleHttp\Client(['handler' => $stack]);
    },
    ListenerProviderInterface::class => static function(): ListenerProviderInterface {
        $provider =new \Crell\Tukio\OrderedListenerProvider(
            ServiceContainer::instance()
        );
        // register events based on attributes on methods in ListenerService
        $provider->listenerService(SpaceTraders\Event\ListenerService::class);
        return $provider;
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
    Route\Router::class => static function (): Route\Router {
        $responseFactory = new Psr7\HttpFactory();
        $strategy = new Route\Strategy\JsonStrategy($responseFactory);
        $router = new Route\Router();
        $router->setStrategy($strategy);
        // Register Middleware Components
        $router->middleware(
            new Middleware\Auth(getSpaceTradersToken())
        );
        $router->middleware(
            new Middleware\ExceptionDecorator(
                ServiceContainer::get(\Twig\Environment::class)
            )
        );

        return $router;
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
    },
];