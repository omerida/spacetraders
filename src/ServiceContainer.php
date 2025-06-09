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
        self::registerApiClients($ref, $_ENV['USE_APCU'] === 1);
    }

    protected static function isAPIClient(ReflectionClass $class): bool
    {
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
    }

    /**
     * Automatically register children of \Phparch\SpaceTraders\Client
     */
    protected static function registerApiClients(BetterReflection $ref, bool $useAPCU): void
    {
        // Use this class and method to build the key for saved data
        $cacheKey = __CLASS__ . '::' . __FUNCTION__;
        // Check if we find anything and that fetch didn't fail
        $classNames = $useAPCU ? apcu_fetch($cacheKey, $success) : [];
        if (!$classNames || !$success) {
            $clients = array_filter(
                self::getSrcClasses($ref),
                [__CLASS__, 'isAPIClient'],
            );
            // Can't cache BetterReflection classes. We just need their names
            $classNames = array_map(
                fn(ReflectionClass $client) => $client->getName(),
                $clients
            ) ;
            apcu_store($cacheKey, $classNames);
        }

        foreach ($classNames as $className) {
            self::$container->set(
                // Classname
                $className,
                // Closure to call when this class is requested.
                function () use ($className) {
                    return new $className(
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
