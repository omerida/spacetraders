<?php
// Provides a connection for Doctrine migrations

use Doctrine\DBAL;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Phparch\SpaceTraders\ServiceContainer;

require __DIR__ . '/../bootstrap.php';

$ORMConfig = ORMSetup::createAttributeMetadataConfig(
    paths: [__DIR__ . '/../src/Entity'],
    isDevMode: true
);
$connection = ServiceContainer::get(DBAL\Connection::class);

$entityManager = new EntityManager($connection, $ORMConfig);

return DependencyFactory::fromEntityManager(
    configurationLoader: new PhpFile(__DIR__ . '/../migrations.php'),
    emLoader: new ExistingEntityManager($entityManager)
);
