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
final class Version20201221075753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD symbol VARCHAR(10) NOT NULL, DROP avg_cost');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F5556B180E');
        $this->addSql('DROP INDEX IDX_462CE4F5556B180E ON position');
        $this->addSql('ALTER TABLE
          position
        ADD
          symbol VARCHAR(10) NOT NULL,
        DROP
          ticker_id,
        DROP
          exchange,
        DROP
          market_value,
        DROP
          change_today,
        DROP
          cost_basis');
        $this->addSql('ALTER TABLE
          ticker
        ADD
          exchange VARCHAR(100) NOT NULL,
        ADD
          sector VARCHAR(100) NOT NULL,
        ADD
          updated_at DATETIME NOT NULL,
        DROP
          currency');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD avg_cost DOUBLE PRECISION DEFAULT \'0\' NOT NULL, DROP symbol');
        $this->addSql('ALTER TABLE
          position
        ADD
          ticker_id INT UNSIGNED NOT NULL,
        ADD
          exchange VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        ADD
          market_value DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
        ADD
          change_today DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
        ADD
          cost_basis DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
        DROP
          symbol');
        $this->addSql('ALTER TABLE
          position
        ADD
          CONSTRAINT FK_462CE4F5556B180E FOREIGN KEY (ticker_id) REFERENCES ticker (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_462CE4F5556B180E ON position (ticker_id)');
        $this->addSql('ALTER TABLE
          ticker
        ADD
          currency VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        DROP
          exchange,
        DROP
          sector,
        DROP
          updated_at');
    }
}
