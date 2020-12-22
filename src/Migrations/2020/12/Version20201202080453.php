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
final class Version20201202080453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding additional fields to Job';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          job
        ADD
          status VARCHAR(100) DEFAULT NULL AFTER `description`,
        ADD
          error_message VARCHAR(225) DEFAULT NULL AFTER `status`,
        ADD
          error_trace TEXT DEFAULT NULL AFTER `error_message`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job DROP status, DROP error_message, DROP error_trace');
    }
}
