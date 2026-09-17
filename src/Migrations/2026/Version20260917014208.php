<?php

declare(strict_types=1);

namespace Phparch\SpaceTraders\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260917014208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds event_records, and market_trade_goods_activity tables.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE event_record (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              created_at DATETIME NOT NULL,
              data CLOB DEFAULT NULL,
              name VARCHAR(512) NOT NULL,
              source VARCHAR(512) NOT NULL,
              description CLOB DEFAULT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE market_trade_goods_activity (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              waypointSymbol VARCHAR(256) NOT NULL,
              symbol VARCHAR(256) NOT NULL,
              type VARCHAR(256) NOT NULL,
              supply VARCHAR(256) NOT NULL,
              activity VARCHAR(256) NOT NULL,
              tradeVolume INTEGER NOT NULL,
              purchasePrice INTEGER NOT NULL,
              sellPrice INTEGER NOT NULL,
              timestamp DATE NOT NULL
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE event_record');
        $this->addSql('DROP TABLE market_trade_goods_activity');
    }
}
