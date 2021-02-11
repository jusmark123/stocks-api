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
final class Version20210123235431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE job ADD failed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE
          job_item
        ADD
          received_at DATETIME DEFAULT NULL,
        ADD
          started_at DATETIME DEFAULT NULL,
        ADD
          processed_at DATETIME DEFAULT NULL,
        ADD
          failed_at DATETIME DEFAULT NULL,
        ADD
          cancelled_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job DROP failed_at');
        $this->addSql('ALTER TABLE
          job_item
        DROP
          received_at,
        DROP
          started_at,
        DROP
          processed_at,
        DROP
          failed_at,
        DROP
          cancelled_at');
    }
}
