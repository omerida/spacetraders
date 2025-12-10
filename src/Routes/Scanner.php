<?php

namespace Phparch\SpaceTraders\Routes;

use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\RouteInfo;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

/**
 * Looks recursively in $root folder for any methods with the
 * Route attribute
 */
class Scanner
{
    public function __construct(
        /**
         * @var array<array{namespace: string, path: string}>
         */
        private readonly array $controllerDirs,
        private readonly BetterReflection $ref,
        private readonly bool $useAPCu,
    ) {
    }

    /**
     * 1. Build an array of the discovered routes that we
     *    can cache so we don't do this on every request.
     *
     * @return RouteInfo[]
     */
    public function discoverRoutes(): array
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
}
