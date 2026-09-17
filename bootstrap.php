<?php
// Common bootstrap for different runtimes

use Doctrine\DBAL\Types\Type;
use Phparch\SpaceTraders\Doctrine\Type\WaypointSymbolType;
use Phparch\SpaceTraders\ServiceContainer;

// include the Composer autoloader
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get our configured service container
$services = require_once __DIR__ . '/config/services.php';

ServiceContainer::config($services);
ServiceContainer::setEnv($_ENV);
// Register dynamic services
ServiceContainer::autodiscover();
// Register Doctrine Types
Type::addType(WaypointSymbolType::NAME, WaypointSymbolType::class);