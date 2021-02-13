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
final class Version20210123170423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add additional event specific datetime fields to job entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          job
        ADD
          received_at DATETIME DEFAULT NULL,
        ADD
          started_at DATETIME DEFAULT NULL,
        ADD
          processed_at DATETIME DEFAULT NULL,
        ADD
          cancelled_at DATETIME DEFAULT NULL,
        ADD
          completed_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          job
        DROP
          received_at,
        DROP
          started_at,
        DROP
          processed_at,
        DROP
          cancelled_at,
        DROP
          completed_at');
    }
}
