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
final class Version20201204052915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added json data field and indexes to job table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job ADD data JSON NOT NULL COMMENT \'(DC2Type:json_document)\' AFTER description');
        $this->addSql('CREATE INDEX job_ix_name_user_id ON job (name, user_id)');
        $this->addSql('CREATE INDEX job_ix_name_source_id ON job (name, source_id)');
        $this->addSql('CREATE INDEX job_ix_name_account_id ON job (name, source_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX job_ix_name_user_id ON job');
        $this->addSql('DROP INDEX job_ix_name_source_id ON job');
        $this->addSql('DROP INDEX job_ix_name_account_id ON job');
        $this->addSql('ALTER TABLE job DROP data');
    }
}
