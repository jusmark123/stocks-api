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
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE topic (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          name VARCHAR(255) NOT NULL,
          topic_arn VARCHAR(255) NOT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          INDEX topic_ix_topic_arn (topic_arn),
          UNIQUE INDEX topic_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE topic_subscription (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          topic_id INT UNSIGNED NOT NULL,
          endpoint VARCHAR(255) NOT NULL,
          protocol VARCHAR(10) NOT NULL,
          confirmed TINYINT(1) DEFAULT \'0\' NOT NULL,
          subscription_arn VARCHAR(255) DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          INDEX IDX_FDC3E3471F55203D (topic_id),
          INDEX topic_subscription_ix_subscription_arn (subscription_arn),
          UNIQUE INDEX topic_subscription_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          topic_subscription
        ADD
          CONSTRAINT FK_FDC3E3471F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
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
        $this->addSql('ALTER TABLE topic_subscription DROP FOREIGN KEY FK_FDC3E3471F55203D');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP TABLE topic_subscription');
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
