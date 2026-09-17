#!/usr/bin/env php
<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Phparch\SpaceTraders\ServiceContainer;

require __DIR__ . '/../bootstrap.php';

// replace with mechanism to retrieve EntityManager in your app
$entityManager = ServiceContainer::get(Doctrine\ORM\EntityManagerInterface::class);
$commands = [
    // If you want to add your own custom console commands,
    // you can do so here.
];
ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    $commands
);