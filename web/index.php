<?php declare(strict_types=1);

use GuzzleHttp\Psr7;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route;
use Phparch\SpaceTraders\Routes\Mapper;
use Phparch\SpaceTraders\ServiceContainer;

require_once __DIR__ . '/../bootstrap.php';

$request = Psr7\ServerRequest::fromGlobals();

$router = ServiceContainer::get(Route\Router::class);
$mapper = ServiceContainer::get(Mapper::class);
$router = $mapper->registerAll($router);

$response = $router->dispatch($request);

// send the response to the browser
(new SapiEmitter)->emit($response);