<?php
// Doctrine Migrations configuration
return [
    'table_storage' => [
        // tracks migrations
        'table_name' => 'doctrine_migration_versions',
        'version_column_name' => 'version',
        'version_column_length' => 191,
        'executed_at_column_name' => 'executed_at',
        'execution_time_column_name' => 'execution_time',
    ],
    // Ignore 'system_registry' and any table starting with 'sys_'
    'schema_filter' => '/^(?!registry_)/',

    'migrations_paths' => [
        'Phparch\SpaceTraders\Migrations' => __DIR__ . '/src/Migrations',
    ],
    // wrap multiple migrations into one transaction
    'all_or_nothing' => true,  // default is false
    // use transactions
    'transactional' => true,
    'check_database_platform' => true,
    // optionally organize migrations by year or year+month
    'organize_migrations' => 'year',
];