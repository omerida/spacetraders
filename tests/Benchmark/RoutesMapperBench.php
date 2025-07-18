<?php

namespace Phparch\SpaceTraders\Tests\Benchmark;

use Dotenv\Dotenv;
use GuzzleHttp\Psr7;
use League\Route;
use Phparch\SpaceTraders\RoutesMapper;
use Phparch\SpaceTraders\ServiceContainer;

class RoutesMapperBench {
    public function benchWithoutCache(): void {
        $root = dirname(__DIR__) . '/../';

        $dotenv = Dotenv::createImmutable($root);
        $dotenv->load();

        $mapper = new RoutesMapper(
            srcRootDir: $root . '/src/',
            controllerDirs: [
                [
                    'namespace' => 'Phparch\\SpaceTraders',
                    'path' => $root . '/src/Controller/'
                ]
            ],
            container: $this->getContainer(),
            useAPCu: false
        );

        $responseFactory = new Psr7\HttpFactory();
        $strategy = new Route\Strategy\JsonStrategy($responseFactory);
        $router   = (new Route\Router)->setStrategy($strategy);
        $mapper = ServiceContainer::get(RoutesMapper::class);
        $router = $mapper->registerAll($router);
    }

    public function benchWithCache(): void {
        $root = dirname(__DIR__) . '/../';

        $dotenv = Dotenv::createMutable($root);
        $dotenv->load();

        $mapper = new RoutesMapper(
            srcRootDir: $root . '/src/',
            controllerDirs: [
                [
                    'namespace' => 'Phparch\\SpaceTraders',
                    'path' => $root . '/src/Controller/'
                ]
            ],
            container: $this->getContainer(),
            useAPCu: true
        );

        $responseFactory = new Psr7\HttpFactory();
        $strategy = new Route\Strategy\JsonStrategy($responseFactory);
        $router   = (new Route\Router)->setStrategy($strategy);

        $router = $mapper->registerAll($router);

    }

    private function getContainer(): \DI\Container
    {
        static $container;

        if (isset($container)) {
            return $container;
        }
        $root = dirname(__DIR__) . '/../';
        $services = require_once $root . '/config/services.php';
        ServiceContainer::config($services);

        // Register dynamic services
        ServiceContainer::autodiscover();
        $container = ServiceContainer::instance();

        return $container;
    }
}