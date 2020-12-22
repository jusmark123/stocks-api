<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201207173316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE algorithm (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          source_id INT UNSIGNED NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          filename VARCHAR(100) NOT NULL,
          config TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          INDEX IDX_9505CCB9953C1C61 (source_id),
          UNIQUE INDEX algorithm_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE screener (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          source_id INT UNSIGNED NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          filename VARCHAR(100) NOT NULL,
          config TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          INDEX IDX_2AB257C953C1C61 (source_id),
          UNIQUE INDEX screener_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stream (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          description TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          UNIQUE INDEX stream_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticker (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          ticker_type_id INT UNSIGNED DEFAULT NULL,
          ticker VARCHAR(10) NOT NULL,
          name VARCHAR(100) NOT NULL,
          market VARCHAR(25) NOT NULL,
          currency VARCHAR(5) NOT NULL,
          active TINYINT(1) NOT NULL,
          url VARCHAR(255) NOT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          INDEX IDX_7EC30896BA9AAC4B (ticker_type_id),
          UNIQUE INDEX ticker_un_guid (guid),
          UNIQUE INDEX ticker_un_name (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticker_type (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          name VARCHAR(100) NOT NULL,
          code VARCHAR(5) NOT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          UNIQUE INDEX ticker_type_un_guid (guid),
          UNIQUE INDEX ticker_type_un_name (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE watchlist (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          type VARCHAR(50) NOT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          UNIQUE INDEX watch_list_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          algorithm
        ADD
          CONSTRAINT FK_9505CCB9953C1C61 FOREIGN KEY (source_id) REFERENCES source (id)');
        $this->addSql('ALTER TABLE
          screener
        ADD
          CONSTRAINT FK_2AB257C953C1C61 FOREIGN KEY (source_id) REFERENCES source (id)');
        $this->addSql('ALTER TABLE
          ticker
        ADD
          CONSTRAINT FK_7EC30896BA9AAC4B FOREIGN KEY (ticker_type_id) REFERENCES ticker_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticker DROP FOREIGN KEY FK_7EC30896BA9AAC4B');
        $this->addSql('DROP TABLE algorithm');
        $this->addSql('DROP TABLE screener');
        $this->addSql('DROP TABLE stream');
        $this->addSql('DROP TABLE ticker');
        $this->addSql('DROP TABLE ticker_type');
        $this->addSql('DROP TABLE watchlist');
    }
}
