<?php

namespace Phparch\SpaceTraders;

use DI\Container;
use GuzzleHttp\Psr7\HttpFactory;
use http\Exception\InvalidArgumentException;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Controller\Abstract\TwigAwareController;
use Psr\Http\Message\ServerRequestInterface;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Twig\Environment;

/**
 * Looks recursively in $root folder for any methods with the
 * Route attribute and registers it in our router.
 */
class RoutesMapper
{
    private BetterReflection $ref;

    public function __construct(
        /** @phpstan-ignore property.onlyWritten */
        private readonly string $srcRootDir,
        /**
         * @var array<array{namespace: string, path: string}>
         */
        private readonly array $controllerDirs,
        private Container $container,
        private bool $useAPCu,
    ) {
        $this->ref = new BetterReflection();
    }

    public function registerAll(Router $router): Router
    {
        // 1. Build an array of the discovered routes that we
        //    can cache so we don't do this on every request.
        $discovered = $this->discoverRoutes();

        foreach ($discovered as $routeInfo) {
            $router = $this->registerOne($router, $routeInfo);
        }

        return $router;
    }

    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    private function registerOne(
        Router $router,
        RouteInfo $info
    ): Router {
        $controller = $this->container->get($info->class);

        if ($controller instanceof TwigAwareController) {
            $controller->setTwigEnvironment(
                $this->container->get(Environment::class)
            );
        }


        $route = $router->map(
            $info->httpMethods,
            $info->path,
            function (ServerRequestInterface $request) use ($controller, $info): mixed {
                if ($controller instanceof RequestAwareInterface) {
                    $controller->setRequest($request);
                }
                return $controller->{$info->method}();
            }
        );

        if ($info->strategy) {
            switch ($info->strategy) {
                case 'application':
                    $route->setStrategy(new ApplicationStrategy());
                    break;
                case 'json':
                    $responseFactory = new HttpFactory();
                    $route->setStrategy(new JsonStrategy($responseFactory));
                    break;
                default:
                    throw new InvalidArgumentException("Unknown route response strategy");
            }
        }

        return $router;
    }

    /**
     * @return \Roave\BetterReflection\Reflection\ReflectionClass[]
     */
    private function findControllers(): array
    {
        $controllers = [];
        $astLocator = $this->ref->astLocator();

        foreach ($this->controllerDirs as $dir) {
            $reflector = new DefaultReflector(
                new DirectoriesSourceLocator([$dir['path']], $astLocator)
            );
            $classes = $reflector->reflectAllClasses();
            array_push($controllers, ...$classes);
        }
        return $controllers;
    }

    /**
     * @return RouteInfo[]
     */
    private function discoverRoutes(): array
    {
        // Use this class and method to build the key for saved data
        $cacheKey = __CLASS__ . '::' . __FUNCTION__;
        // Check if we find anything and that fetch didn't fail
        $success = false;
        $discovered = $this->useAPCu ? apcu_fetch($cacheKey, $success) : [];
        if ($discovered && $success) {
            /** @var RouteInfo[] $discovered */
            return $discovered;
        }

        $controllers = $this->findControllers();

        $discovered = [];
        foreach ($controllers as $classInfo) {
            $methods = $classInfo->getImmediateMethods(
                filter: \ReflectionMethod::IS_PUBLIC
            );
            foreach ($methods as $method) {
                if ($method->getName() === '__construct') {
                    continue;
                }

                if ($attrs = $method->getAttributesByName(Route::class)) {
                    foreach ($attrs as $attr) {
                        /**
                         * @var array{
                         *     name: string,
                         *     path: string,
                         *     methods: string[],
                         *     strategy ?: 'application'|'json',
                         * } $args
                         */
                        $args = $attr->getArguments();

                        if (isset($discovered[$args['name']])) {
                            throw new \RuntimeException(
                                'A route with this name already exists: '
                                . $args['name']
                            );
                        }

                        $discovered[$args['name']] = new RouteInfo(
                            name: $args['name'],
                            path: $args['path'],
                            httpMethods: $args['methods'],
                            class: $classInfo->getName(),
                            method: $method->getName(),
                            strategy: $args['strategy'] ?? null
                        );
                    }
                }
            }
        }

        apcu_store($cacheKey, $discovered);
        return $discovered;
    }
}
