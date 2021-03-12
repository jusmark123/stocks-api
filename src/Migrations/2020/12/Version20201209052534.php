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
final class Version20201209052534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add AlpacaAccount and Source to job table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          job
        CHANGE
          account_id account_id INT UNSIGNED DEFAULT NULL,
        CHANGE
          source_id source_id INT UNSIGNED DEFAULT NULL,
        CHANGE
          error_message error_message VARCHAR(22) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          job
        CHANGE
          account_id account_id INT UNSIGNED NOT NULL,
        CHANGE
          source_id source_id INT UNSIGNED NOT NULL,
        CHANGE
          error_message error_message VARCHAR(225) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
