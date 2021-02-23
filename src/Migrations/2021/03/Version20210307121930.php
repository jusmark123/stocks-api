<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210307121930 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ticker_sector (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          name VARCHAR(200) NOT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          UNIQUE INDEX source_type_un_name (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticker ADD sector_id INT UNSIGNED DEFAULT NULL, DROP sector');
        $this->addSql('ALTER TABLE
          ticker
        ADD
          CONSTRAINT FK_7EC30896DE95C867 FOREIGN KEY (sector_id) REFERENCES ticker_sector (id)');
        $this->addSql('CREATE INDEX IDX_7EC30896DE95C867 ON ticker (sector_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticker DROP FOREIGN KEY FK_7EC30896DE95C867');
        $this->addSql('DROP TABLE ticker_sector');
        $this->addSql('DROP INDEX IDX_7EC30896DE95C867 ON ticker');
        $this->addSql('ALTER TABLE
          ticker
        ADD
          sector VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
        DROP
          sector_id');
    }
}
