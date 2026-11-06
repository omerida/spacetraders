<?php

declare(strict_types=1);

namespace Phparch\SpaceTraders\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261022041112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds tables for ship orders and queues.';
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
              type VARCHAR(255) NOT NULL,
              payload CLOB NOT NULL,
              priority INTEGER NOT NULL,
              queue_id INTEGER NOT NULL,
              CONSTRAINT FK_1397A2E6477B5BAE FOREIGN KEY (queue_id) 
                  REFERENCES ship_order_queue (id) 
                  ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_1397A2E6477B5BAE ON ship_orders (queue_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ship_order_queue');
        $this->addSql('DROP TABLE ship_orders');
    }
}
