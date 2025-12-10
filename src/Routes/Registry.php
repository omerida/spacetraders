<?php

namespace Phparch\SpaceTraders\Routes;

use DI\Container;
use GuzzleHttp\Psr7\HttpFactory;
use http\Exception\InvalidArgumentException;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;
use Phparch\SpaceTraders\RequestAwareInterface;
use Phparch\SpaceTraders\RouteInfo;
use Phparch\SpaceTraders\TwigAwareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class Registry
{
    public function __construct(
        private Container $container,
        public Router $router,
        private Decorator $decorator
    ) {
    }

    public function getRouter(): Router
    {
        return $this->router;
    }


    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function registerOne(
        RouteInfo $info
    ): void {
        $controller = $this->container->get($info->class);

        $twig = $this->container->get(Environment::class);

        if (
            $twig instanceof Environment
            && is_object($controller)
        ) {
            $this->decorator->applyTwigEnvironment(
                $controller,
                $twig,
            );
        }

        $route = $this->router->map(
            $info->httpMethods,
            $info->path,
            function (ServerRequestInterface $request) use ($controller, $info): mixed {
                if ($controller instanceof RequestAwareInterface) {
                    $controller->setRequest($request);
                }
                return $controller->{$info->method}();
            }
        );

        $this->decorator->applyStrategy($route, $info);
    }
}
