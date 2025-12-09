<?php declare(strict_types=1);

use GuzzleHttp\Psr7;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route;
use Phparch\SpaceTraders\Middleware;
use Phparch\SpaceTraders\RoutesMapper;
use Phparch\SpaceTraders\ServiceContainer;

// include the Composer autoloader
require '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Get our configured service container
$services = require_once __DIR__ . '/../config/services.php';
ServiceContainer::config($services);
ServiceContainer::setEnv($_ENV);
// Register dynamic services
ServiceContainer::autodiscover();

$request = Psr7\ServerRequest::fromGlobals();

$responseFactory = new Psr7\HttpFactory();
$strategy = new Route\Strategy\JsonStrategy($responseFactory);
$router   = (new Route\Router)->setStrategy($strategy);
// Register Middleware Components
$router->middleware(new Middleware\Auth($_ENV["SPACETRADERS_TOKEN"] ?? ''));
$router->middleware(new Middleware\ExceptionDecorator(
    ServiceContainer::get(\Twig\Environment::class)
));

$mapper = ServiceContainer::get(RoutesMapper::class);
$router = $mapper->registerAll($router);

$response = $router->dispatch($request);

// send the response to the browser
(new SapiEmitter)->emit($response);