<?php

namespace Phparch\SpaceTraders\Routes;

use DI\DependencyException;
use DI\NotFoundException;
use League\Route\Router;

/**
 * Registers discovered paths in the router
 */
class Mapper
{
    public function __construct(
        private Scanner $scanner,
        private Registry $registry,
    ) {
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function registerAll(): Router
    {
        $discovered = $this->scanner->discoverRoutes();

        foreach ($discovered as $routeInfo) {
            $this->registry->registerOne($routeInfo);
        }

        return $this->registry->getRouter();
    }
}
