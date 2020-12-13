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
final class Version20201210173826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added Source to position table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position ADD source_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE
          position
        ADD
          CONSTRAINT FK_462CE4F5953C1C61 FOREIGN KEY (source_id) REFERENCES source (id)');
        $this->addSql('CREATE INDEX IDX_462CE4F5953C1C61 ON position (source_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F5953C1C61');
        $this->addSql('DROP INDEX IDX_462CE4F5953C1C61 ON position');
        $this->addSql('ALTER TABLE position DROP source_id');
    }
}
