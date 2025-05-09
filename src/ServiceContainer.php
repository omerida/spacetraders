<?php

namespace Phparch\SpaceTraders;

use DI;
use DI\Container;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

final class ServiceContainer
{
    private static Container $container;

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
        self::registerApiClients($ref);
    }

    /**
     * Automatically register children of \Phparch\SpaceTraders\Client
     */
    protected static function registerApiClients(BetterReflection $ref): void
    {
        $is_client = static function (ReflectionClass $class): bool {
            $name = $class->getNamespaceName();
            if ($name !== 'Phparch\SpaceTraders\Client') {
                return false;
            }

            if (
                in_array(
                    needle: 'Phparch\SpaceTraders\Client',
                    haystack: $class->getParentClassNames(),
                    strict: true
                )
            ) {
                return true;
            }

            return false;
        };

        $clients = array_filter(
            self::getSrcClasses($ref),
            $is_client,
        );

        foreach ($clients as $client) {
            self::$container->set(
                // Classname
                $client->getName(),
                // Closure to call when this class is requested.
                function () use ($client) {
                    $clientClass = $client->getName();
                    return new $clientClass(
                        $_ENV['SPACETRADERS_TOKEN'],
                        self::get(\GuzzleHttp\Client::class)
                    );
                }
            );
        }
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
