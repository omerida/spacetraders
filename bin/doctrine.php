#!/usr/bin/env php
<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Phparch\SpaceTraders\Doctrine\Command\TruncateTablesCommand;
use Phparch\SpaceTraders\ServiceContainer;

require __DIR__ . '/../bootstrap.php';

// replace with mechanism to retrieve EntityManager in your app
$entityManager = ServiceContainer::get(Doctrine\ORM\EntityManagerInterface::class);
$commands = [
    new TruncateTablesCommand($entityManager)
];
ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    $commands
);