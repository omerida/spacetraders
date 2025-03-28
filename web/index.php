<?php declare(strict_types=1);

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Middleware\Auth;
use Psr\Http\Message\ServerRequestInterface;
use Phparch\SpaceTraders\ServiceContainer;

// include the Composer autoloader
require '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Get our configured service container
$services = require_once __DIR__ . '/../config/services.php';
ServiceContainer::config($services);

$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();

$responseFactory = new \GuzzleHttp\Psr7\HttpFactory();
$strategy = new League\Route\Strategy\JsonStrategy($responseFactory);
$router   = (new League\Route\Router)->setStrategy($strategy);
$router->middleware(new Auth($_ENV["SPACETRADERS_TOKEN"] ?? ''));

// map a route
$router->map('GET', '/', function (ServerRequestInterface $request): array {
    $now = new \DateTimeImmutable('now');
    return [
        'msg' => 'Hello, world.',
        'now' => $now->format(DATE_ATOM)
    ];
});

$router->map(
    'GET', '/my/agent',
    function (ServerRequestInterface $request): array {
        $client = ServiceContainer::get(Client\Agents::class);
        return (array) $client->myAgent();
    }
);

$router->map(
    'GET', '/my/ships',
    function (ServerRequestInterface $request): array {
        $client = ServiceContainer::get(Client\Fleet::class);
        return (array) $client->listShips();
    }
);

$router->map(
    'GET', '/my/contracts',
    function (ServerRequestInterface $request): array {
        $client = ServiceContainer::get(Client\Contracts::class);
        return (array) $client->myContracts();
    }
);

$response = $router->dispatch($request);

// send the response to the browser
(new SapiEmitter)->emit($response);