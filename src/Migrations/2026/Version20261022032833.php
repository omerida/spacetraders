<?php

declare(strict_types=1);

namespace Phparch\SpaceTraders\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261022032833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add ship orders and queue tables.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE ship_order_queue (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              shipSymbol VARCHAR(255) NOT NULL,
              queueMode VARCHAR(255) NOT NULL,
              current INTEGER NOT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ship_orders (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              status VARCHAR(255) NOT NULL,
              shipSymbol VARCHAR(255) NOT NULL,
              type VARCHAR(255) NOT NULL,
              payload CLOB NOT NULL,
              priority INTEGER NOT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__market_trade_goods_activity AS
            SELECT
              id,
              waypointSymbol,
              symbol,
              type,
              supply,
              activity,
              tradeVolume,
              purchasePrice,
              sellPrice,
              timestamp
            FROM
              market_trade_goods_activity
        SQL);
        $this->addSql('DROP TABLE market_trade_goods_activity');
        $this->addSql(<<<'SQL'
            CREATE TABLE market_trade_goods_activity (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              waypointSymbol VARCHAR(255) NOT NULL,
              symbol CLOB NOT NULL,
              type CLOB NOT NULL,
              supply CLOB NOT NULL,
              activity CLOB NOT NULL,
              tradeVolume INTEGER NOT NULL,
              purchasePrice INTEGER NOT NULL,
              sellPrice INTEGER NOT NULL,
              timestamp DATE NOT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO market_trade_goods_activity (
              id, waypointSymbol, symbol, type, supply,
              activity, tradeVolume, purchasePrice,
              sellPrice, timestamp
            )
            SELECT
              id,
              waypointSymbol,
              symbol,
              type,
              supply,
              activity,
              tradeVolume,
              purchasePrice,
              sellPrice,
              timestamp
            FROM
              __temp__market_trade_goods_activity
        SQL);
        $this->addSql('DROP TABLE __temp__market_trade_goods_activity');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ship_order_queue');
        $this->addSql('DROP TABLE ship_orders');
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__market_trade_goods_activity AS
            SELECT
              id,
              waypointSymbol,
              symbol,
              type,
              supply,
              activity,
              tradeVolume,
              purchasePrice,
              sellPrice,
              timestamp
            FROM
              market_trade_goods_activity
        SQL);
        $this->addSql('DROP TABLE market_trade_goods_activity');
        $this->addSql(<<<'SQL'
            CREATE TABLE market_trade_goods_activity (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              waypointSymbol VARCHAR(256) NOT NULL,
              symbol VARCHAR(256) NOT NULL,
              type VARCHAR(256) NOT NULL,
              supply VARCHAR(256) NOT NULL,
              activity VARCHAR(256) DEFAULT NULL,
              tradeVolume INTEGER NOT NULL,
              purchasePrice INTEGER NOT NULL,
              sellPrice INTEGER NOT NULL,
              timestamp DATE NOT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO market_trade_goods_activity (
              id, waypointSymbol, symbol, type, supply,
              activity, tradeVolume, purchasePrice,
              sellPrice, timestamp
            )
            SELECT
              id,
              waypointSymbol,
              symbol,
              type,
              supply,
              activity,
              tradeVolume,
              purchasePrice,
              sellPrice,
              timestamp
            FROM
              __temp__market_trade_goods_activity
        SQL);
        $this->addSql('DROP TABLE __temp__market_trade_goods_activity');
    }
}
