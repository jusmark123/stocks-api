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
final class Version20201129021348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding UserType table and UserType to User table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_type (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          UNIQUE INDEX user_type_un_name (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE user ADD user_type_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE
          user
        ADD
          CONSTRAINT FK_8D93D6499D419299 FOREIGN KEY (user_type_id) REFERENCES user_type (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6499D419299 ON user (user_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499D419299');
        $this->addSql('DROP INDEX IDX_8D93D6499D419299 ON user');
        $this->addSql('ALTER TABLE user DROP user_type_id');
        $this->addSql('DROP TABLE user_type');
    }
}
