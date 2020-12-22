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
final class Version20201213235033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added intermediate table for Brokerage/Ticker relationship';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brokerage_ticker (
          brokerage_id INT UNSIGNED NOT NULL,
          ticker_id INT UNSIGNED NOT NULL,
          INDEX IDX_5DFC3B1379969C22 (brokerage_id),
          INDEX IDX_5DFC3B13556B180E (ticker_id),
          PRIMARY KEY(brokerage_id, ticker_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          brokerage_ticker
        ADD
          CONSTRAINT FK_5DFC3B1379969C22 FOREIGN KEY (brokerage_id) REFERENCES brokerage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          brokerage_ticker
        ADD
          CONSTRAINT FK_5DFC3B13556B180E FOREIGN KEY (ticker_id) REFERENCES ticker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` DROP side');
        $this->addSql('DROP INDEX position_ix_symbol ON position');
        $this->addSql('DROP INDEX position_un_symbol_account_id ON position');
        $this->addSql('ALTER TABLE position ADD ticker_id INT UNSIGNED NOT NULL, DROP symbol');
        $this->addSql('ALTER TABLE
          position
        ADD
          CONSTRAINT FK_462CE4F5556B180E FOREIGN KEY (ticker_id) REFERENCES ticker (id)');
        $this->addSql('CREATE INDEX IDX_462CE4F5556B180E ON position (ticker_id)');
        $this->addSql('ALTER TABLE ticker CHANGE ticker symbol VARCHAR(10) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE brokerage_ticker');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          side VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F5556B180E');
        $this->addSql('DROP INDEX IDX_462CE4F5556B180E ON position');
        $this->addSql('ALTER TABLE
          position
        ADD
          symbol VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        DROP
          ticker_id');
        $this->addSql('CREATE INDEX position_ix_symbol ON position (symbol)');
        $this->addSql('CREATE UNIQUE INDEX position_un_symbol_account_id ON position (symbol, account_id)');
        $this->addSql('ALTER TABLE
          ticker
        CHANGE
          symbol ticker VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
