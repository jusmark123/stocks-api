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
final class Version20210102073733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP account_number');
        $this->addSql('ALTER TABLE ticker DROP INDEX ticker_un_name, ADD INDEX ticker_ix_name (name)');
        $this->addSql('ALTER TABLE
          ticker
        DROP
          market,
        DROP
          url,
        DROP
          updated_at,
        CHANGE
          sector sector VARCHAR(100) DEFAULT NULL,
        CHANGE
          symbol ticker VARCHAR(10) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX ticker_un_ticker ON ticker (ticker)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          account
        ADD
          account_number VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ticker DROP INDEX ticker_ix_name, ADD UNIQUE INDEX ticker_un_name (name)');
        $this->addSql('DROP INDEX ticker_un_ticker ON ticker');
        $this->addSql('ALTER TABLE
          ticker
        ADD
          market VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        ADD
          url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        ADD
          updated_at DATETIME NOT NULL,
        CHANGE
          sector sector VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        CHANGE
          ticker symbol VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
