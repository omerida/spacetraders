<?php

namespace Phparch\SpaceTraders;

use DI\Container;
use League\Route\Router;
use Phparch\SpaceTraders\Attribute\Route;
use Psr\Http\Message\ServerRequestInterface;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

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
    ) {
        $this->ref = new BetterReflection();
    }

    public function registerAll(Router $router): Router
    {
        $controllers = $this->findControllers();

        // 1. Build an array of the discovered routes that we
        //    can cache so we don't do this on every request.
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
                        );
                    }
                }
            }
        }

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

        $router->map(
            $info->httpMethods,
            $info->path,
            function (ServerRequestInterface $request) use ($controller, $info): mixed {
                if ($controller instanceof RequestAwareInterface) {
                    $controller->setRequest($request);
                }
                return $controller->{$info->method}();
            }
        );

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
            $controllers = array_merge($controllers, $classes);
        }

        return $controllers;
    }
}
