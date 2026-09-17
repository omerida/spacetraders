<?php

declare(strict_types=1);

namespace Phparch\SpaceTraders\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260917014453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Manages the registry tables that do not use Entity Manager.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
        CREATE TABLE IF NOT EXISTS registry_text (
            name TEXT PRIMARY KEY UNIQUE,
            val TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        SQL);
        $this->addSql(<<<'SQL'
        CREATE TRIGGER IF NOT EXISTS update_registry_text_timestamp
        AFTER UPDATE ON registry_text
        BEGIN
            UPDATE registry_text
            SET updated_at = CURRENT_TIMESTAMP
            WHERE name = OLD.name;
        END;
        SQL);
        $this->addSql(<<<'SQL'
        CREATE TABLE IF NOT EXISTS registry_int (
            name TEXT PRIMARY KEY UNIQUE,
            val INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        SQL);
        $this->addSql(<<<'SQL'
        CREATE TRIGGER IF NOT EXISTS update_registry_int_timestamp
        AFTER UPDATE ON registry_int
        BEGIN
            UPDATE registry_int
            SET updated_at = CURRENT_TIMESTAMP
            WHERE name = OLD.name;
        END;
        SQL);
        $this->addSql(<<<'SQL'
        CREATE TABLE IF NOT EXISTS registry_bool (
            name TEXT PRIMARY KEY UNIQUE,
            val BOOL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        SQL);
        $this->addSql(<<<'SQL'
        CREATE TRIGGER IF NOT EXISTS update_registry_bool_timestamp
        AFTER UPDATE ON registry_bool
        BEGIN
            UPDATE registry_bool
            SET updated_at = CURRENT_TIMESTAMP
            WHERE name = OLD.name;
        END;
        SQL);
    }

    public function down(Schema $schema): void
    {
        // SQLlite- triggers dropped when associated tables are dropped
        $this->addSql('DROP TABLE registry_bool');
        $this->addSql('DROP TABLE registry_int');
        $this->addSql('DROP TABLE registry_text');
    }
}
