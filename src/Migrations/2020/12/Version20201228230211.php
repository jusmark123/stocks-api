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
final class Version20201228230211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_item (
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
          INDEX IDX_98D7535FBE04EA9 (job_id),
          UNIQUE INDEX job_item_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (
          id BIGINT AUTO_INCREMENT NOT NULL,
          body LONGTEXT NOT NULL,
          headers LONGTEXT NOT NULL,
          queue_name VARCHAR(190) NOT NULL,
          created_at DATETIME NOT NULL,
          available_at DATETIME NOT NULL,
          delivered_at DATETIME DEFAULT NULL,
          INDEX IDX_75EA56E0FB7336F0 (queue_name),
          INDEX IDX_75EA56E0E3BD61CE (available_at),
          INDEX IDX_75EA56E016BA31DB (delivered_at),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          job_item
        ADD
          CONSTRAINT FK_98D7535FBE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('DROP TABLE job_data_item');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_data_item (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          job_id INT UNSIGNED DEFAULT NULL,
          data TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          status VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
          error_message VARCHAR(22) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          error_trace TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          modified_at DATETIME NOT NULL,
          modified_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          guid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\',
          INDEX IDX_63E401EDBE04EA9 (job_id),
          UNIQUE INDEX job_data_item_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\'');
        $this->addSql('ALTER TABLE
          job_data_item
        ADD
          CONSTRAINT FK_63E401EDBE04EA9 FOREIGN KEY (job_id) REFERENCES job (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE job_item');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
