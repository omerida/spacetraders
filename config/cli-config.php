<?php
// Provides a connection for Doctrine migrations

use Doctrine\DBAL;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManagerInterface;
use Phparch\SpaceTraders\ServiceContainer;

require __DIR__ . '/../bootstrap.php';

$connection = ServiceContainer::get(DBAL\Connection::class);
$entityManager = ServiceContainer::get(EntityManagerInterface::class);
return DependencyFactory::fromEntityManager(
    configurationLoader: new PhpFile(__DIR__ . '/../migrations.php'),
    emLoader: new ExistingEntityManager($entityManager)
);
