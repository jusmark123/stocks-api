<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201129021347 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added Job table for task monitoring';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job (
					guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          account_id INT UNSIGNED NOT NULL,
          source_id INT UNSIGNED NOT NULL,
          user_id INT UNSIGNED NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          INDEX IDX_FBD8E0F89B6B5FBA (account_id),
          INDEX IDX_FBD8E0F8953C1C61 (source_id),
          INDEX IDX_FBD8E0F8A76ED395 (user_id),
          UNIQUE INDEX job_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          job
        ADD
          CONSTRAINT FK_FBD8E0F89B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE
          job
        ADD
          CONSTRAINT FK_FBD8E0F8953C1C61 FOREIGN KEY (source_id) REFERENCES source (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE brokerage ADD context VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX brokerage_un_context ON brokerage (context)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP INDEX brokerage_un_context ON brokerage');
        $this->addSql('ALTER TABLE brokerage DROP context');
    }
}
