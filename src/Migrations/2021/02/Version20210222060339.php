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
final class Version20210222060339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('SET FOREIGN_KEY_CHECKS=0;');
        $this->addSql('ALTER TABLE algorithm DROP FOREIGN KEY FK_9505CCB9953C1C61');
        $this->addSql('DROP INDEX IDX_9505CCB9953C1C61 ON algorithm');
        $this->addSql('ALTER TABLE algorithm DROP source_id, CHANGE id id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE
          algorithm
        ADD
          CONSTRAINT FK_9505CCB9BF396750 FOREIGN KEY (id) REFERENCES source (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE screener DROP FOREIGN KEY FK_2AB257C953C1C61');
        $this->addSql('DROP INDEX IDX_2AB257C953C1C61 ON screener');
        $this->addSql('ALTER TABLE screener DROP source_id');
        $this->addSql('ALTER TABLE source DROP FOREIGN KEY source_fk_source_type');
        $this->addSql('DROP INDEX IDX_5F8A7F738C9334FB ON source');
        $this->addSql('DROP INDEX source_un_guid ON source');
        $this->addSql('ALTER TABLE
          source
        ADD
          type VARCHAR(255) NOT NULL,
        DROP
          source_type_id,
        DROP
          guid,
        DROP
          name,
        DROP
          description,
        DROP
          created_at,
        DROP
          created_by,
        DROP
          modified_by,
        DROP
          modified_at,
        DROP
          deactivated_at,
        DROP
          deactivated_by');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649953C1C61');
        $this->addSql('DROP INDEX IDX_8D93D649953C1C61 ON user');
        $this->addSql('ALTER TABLE user DROP source_id, CHANGE id id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE
          user
        ADD
          CONSTRAINT FK_8D93D649BF396750 FOREIGN KEY (id) REFERENCES source (id) ON DELETE CASCADE');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('SET FOREIGN_KEY_CHECKS=0;');
        $this->addSql('ALTER TABLE algorithm DROP FOREIGN KEY FK_9505CCB9BF396750');
        $this->addSql('ALTER TABLE
          algorithm
        ADD
          source_id INT UNSIGNED NOT NULL,
        CHANGE
          id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE
          algorithm
        ADD
          CONSTRAINT FK_9505CCB9953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9505CCB9953C1C61 ON algorithm (source_id)');
        $this->addSql('ALTER TABLE screener ADD source_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE
          screener
        ADD
          CONSTRAINT FK_2AB257C953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2AB257C953C1C61 ON screener (source_id)');
        $this->addSql('ALTER TABLE
          source
        ADD
          source_type_id INT UNSIGNED NOT NULL,
        ADD
          guid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\',
        ADD
          name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        ADD
          description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
        ADD
          created_at DATETIME NOT NULL,
        ADD
          created_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
        ADD
          modified_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
        ADD
          modified_at DATETIME NOT NULL,
        ADD
          deactivated_at DATETIME DEFAULT NULL,
        ADD
          deactivated_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
        DROP
          type');
        $this->addSql('ALTER TABLE
          source
        ADD
          CONSTRAINT source_fk_source_type FOREIGN KEY (source_type_id) REFERENCES source_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5F8A7F738C9334FB ON source (source_type_id)');
        $this->addSql('CREATE UNIQUE INDEX source_un_guid ON source (guid)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BF396750');
        $this->addSql('ALTER TABLE
          user
        ADD
          source_id INT UNSIGNED NOT NULL,
        CHANGE
          id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE
          user
        ADD
          CONSTRAINT FK_8D93D649953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D649953C1C61 ON user (source_id)');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1;');
    }
}
