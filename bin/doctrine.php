#!/usr/bin/env php
<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Phparch\SpaceTraders\ServiceContainer;

// replace with path to your own project bootstrap file
// include the Composer autoloader
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Get our configured service container
$services = require_once __DIR__ . '/../config/services.php';
ServiceContainer::config($services);
ServiceContainer::setEnv($_ENV);
// Register dynamic services
ServiceContainer::autodiscover();

// replace with mechanism to retrieve EntityManager in your app
$entityManager = ServiceContainer::get(Doctrine\ORM\EntityManager::class);
$commands = [
    // If you want to add your own custom console commands,
    // you can do so here.
];
ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    $commands
);