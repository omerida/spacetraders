<?php declare(strict_types=1);

use GuzzleHttp\Psr7;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Middleware\Auth;
use Phparch\SpaceTraders\RoutesMapper;
use Psr\Http\Message\ServerRequestInterface;
use Phparch\SpaceTraders\ServiceContainer;

// include the Composer autoloader
require '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Get our configured service container
$services = require_once __DIR__ . '/../config/services.php';
ServiceContainer::config($services);

// Register dynamic services
ServiceContainer::autodiscover();


$request = Psr7\ServerRequest::fromGlobals();

$responseFactory = new Psr7\HttpFactory();
$strategy = new Route\Strategy\JsonStrategy($responseFactory);
$router   = (new Route\Router)->setStrategy($strategy);
$router->middleware(new Auth($_ENV["SPACETRADERS_TOKEN"] ?? ''));

$mapper = ServiceContainer::get(RoutesMapper::class);

$router = $mapper->registerAll($router);

$router->map('GET', '/', function (ServerRequestInterface $request): array {
    $now = new \DateTimeImmutable('now');
    return [
        'msg' => 'Hello, world.',
        'now' => $now->format(DATE_ATOM)
    ];
});

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