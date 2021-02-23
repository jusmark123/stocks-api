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
final class Version20210222060908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8A76ED395');
        $this->addSql('DROP INDEX IDX_FBD8E0F8A76ED395 ON job');
        $this->addSql('DROP INDEX job_ix_name_user_id ON job');
        $this->addSql('ALTER TABLE job DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job ADD user_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE
          job
        ADD
          CONSTRAINT FK_FBD8E0F8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FBD8E0F8A76ED395 ON job (user_id)');
        $this->addSql('CREATE INDEX job_ix_name_user_id ON job (name, user_id)');
    }
}
