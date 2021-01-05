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
final class Version20201210172036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added JobItem table and collection on job table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_data_item (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          job_id INT UNSIGNED DEFAULT NULL,
          data TEXT DEFAULT NULL,
          status VARCHAR(50) NOT NULL,
          error_message VARCHAR(22) DEFAULT NULL,
          error_trace TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          INDEX IDX_63E401EDBE04EA9 (job_id),
          UNIQUE INDEX job_data_item_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          job_data_item
        ADD
          CONSTRAINT FK_63E401EDBE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE job_data_item');
    }
}
