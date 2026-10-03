<?php

namespace Phparch\SpaceTraders\Doctrine\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TruncateTablesCommand extends Command
{
    protected static string $defaultName = 'app:db:truncate-tables';

    // Tables which should be truncated when Spacetraders resets a game
    private const TABLES_TO_TRUNCATE = [
        'event_record',
        'market_trade_goods_activity',
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:db:truncate-tables')
            ->setDescription(
                'Truncates specified database tables. Use after a Spacetraders server reset.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        $io->warning(
            'You are about to truncate the following tables: '
            . implode(', ', self::TABLES_TO_TRUNCATE)
        );
        if (!$io->confirm('Do you want to continue?', false)) {
            $io->note('Operation cancelled.');
            return Command::SUCCESS;
        }

        try {
            $this->setForeignKeyChecks($connection, $platform, false);

            foreach (self::TABLES_TO_TRUNCATE as $table) {
                $io->text("Truncating table: <comment>{$table}</comment>...");

                $truncateSql = $connection->getDatabasePlatform()
                    ->getTruncateTableSQL($table, true);
                $io->text($truncateSql);
                $connection->executeStatement($truncateSql);
            }

            $this->setForeignKeyChecks($connection, $platform, true);

            $io->success('All specified tables were successfully truncated.');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->setForeignKeyChecks($connection, $platform, true);
            $io->error('An error occurred while truncating tables: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function setForeignKeyChecks(
        Connection $connection,
        Platforms\AbstractPlatform $platform,
        bool $enable
    ): void {
        if ($platform instanceof Platforms\SQLitePlatform) {
            $status = $enable ? 'ON' : 'OFF';
            $connection->executeStatement("PRAGMA foreign_keys = {$status};");
        } elseif ($platform instanceof Platforms\MySQLPlatform) {
            $status = $enable ? '1' : '0';
            $connection->executeStatement("SET FOREIGN_KEY_CHECKS = {$status};");
        }
    }
}
