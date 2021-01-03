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
final class Version20201214000338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added indexes to position table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position RENAME INDEX idx_462ce4f59b6b5fba TO position_ix_account_id');
        $this->addSql('ALTER TABLE position RENAME INDEX idx_462ce4f5953c1c61 TO position_ix_source_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position RENAME INDEX position_ix_source_id TO IDX_462CE4F5953C1C61');
        $this->addSql('ALTER TABLE position RENAME INDEX position_ix_account_id TO IDX_462CE4F59B6B5FBA');
    }
}
