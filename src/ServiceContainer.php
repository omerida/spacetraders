<?php

namespace Phparch\SpaceTraders;

use DI;
use DI\Container;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

/**
 * @phpstan-type Environment array{
 *     USE_APCU: bool,
 *     SPACETRADERS_TOKEN: string
 * }
 */
final class ServiceContainer
{
    private static Container $container;
    /** @var Environment */
    private static array $env;

    /**
     * @param array<class-string, callable> $config
     */
    public static function config(array $config): void
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

    public static function instance(): Container
    {
        return self::$container;
    }

    public static function autodiscover(): void
    {
        $ref = new BetterReflection();
        // TODO - is there a way to do this with an external dependency?
        //self::registerApiClients($ref, self::$env['USE_APCU'] === 1);
    }

    /**
     * @param Environment $env
     */
    public static function setEnv(array $env): void
    {
        self::$env = $env;
    }

    public static function getEnv(string $key): mixed {
        if (!isset(self::$env)) {
            return null;
        }

        return self::$env[$key];
    }
    /**
     * @return ReflectionClass[]
     */
    protected static function getSrcClasses(BetterReflection $ref): array
    {
        $astLocator = $ref->astLocator();
        $reflector = new DefaultReflector(
            new DirectoriesSourceLocator([__DIR__], $astLocator)
        );
        return $reflector->reflectAllClasses();
    }
}
