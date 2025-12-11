<?php

namespace Phparch\SpaceTraders\Routes;

use GuzzleHttp\Psr7\HttpFactory;
use InvalidArgumentException;
use League\Route\Route;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;
use Phparch\SpaceTraders\Interface\TwigAware;
use Phparch\SpaceTraders\RouteInfo;
use Twig\Environment;

class Decorator
{
    public function applyTwigEnvironment(
        object $controller,
        Environment $twig,
    ): void {
        if ($controller instanceof TwigAware) {
            $controller->setTwigEnvironment($twig);
        }
    }

    public function applyStrategy(Route $route, RouteInfo $info): void
    {
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
                    throw new InvalidArgumentException(
                        "Unknown route response strategy"
                    );
            }
            return;
        }

        $responseFactory = new HttpFactory();
        $route->setStrategy(new JsonStrategy($responseFactory));
    }
}
