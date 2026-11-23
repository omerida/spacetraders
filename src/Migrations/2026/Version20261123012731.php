<?php

declare(strict_types=1);

namespace Phparch\SpaceTraders\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261123012731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a next_check column to ship order queues';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ship_order_queue ADD COLUMN next_check DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__ship_order_queue AS
            SELECT
              id,
              shipSymbol,
              queueMode,
              current
            FROM
              ship_order_queue
        SQL);
        $this->addSql('DROP TABLE ship_order_queue');
        $this->addSql(<<<'SQL'
            CREATE TABLE ship_order_queue (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              shipSymbol VARCHAR(255) NOT NULL,
              queueMode VARCHAR(255) NOT NULL,
              current INTEGER NOT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO ship_order_queue (
              id, shipSymbol, queueMode, current
            )
            SELECT
              id,
              shipSymbol,
              queueMode,
              current
            FROM
              __temp__ship_order_queue
        SQL);
        $this->addSql('DROP TABLE __temp__ship_order_queue');
    }
}
