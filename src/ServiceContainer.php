<?php

namespace Phparch\SpaceTraders;

use DI;
use DI\Container;

class ServiceContainer
{
    private static Container $container;

    public static function config(array $config)
    {
        if (!isset(self::$container)) {
            self::$container = new Container();
        }

        foreach ($config as $name => $service) {
            self::$container->set($name, $service);
        }
    }
    /**
     * Returns a service from the Service Container
     *
     * Most services that are implemented as classes should use the class name
     * as the service name. For services that need additional setup or a name
     * that does not match a class name, register the name explicitly in this
     * method.
     *
     * @template T
     * @param class-string<T> $serviceName Name of the Service to return
     * @return T
     * @throws DI\DependencyException
     * @throws DI\NotFoundException
     */
    public static function get(string $serviceName)
    {
        if (!isset(self::$container)) {
            self::$container = new Container();
        }

        return self::$container->get($serviceName);
    }

    /**
     * For when you need a new instance of a class
     *
     * @template T
     * @param class-string<T> $serviceName Name of the Service to return
     * @return T
     * @throws DI\DependencyException
     * @throws DI\NotFoundException
     */
    public static function make(string $serviceName)
    {
        return self::$container->make($serviceName);
    }
}
