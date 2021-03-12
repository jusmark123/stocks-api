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
final class Version20201127035635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'User & AlpacaAccount Entity orm updates';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          account
        ADD
          api_endpoint_url VARCHAR(150) NOT NULL,
        CHANGE
          account_number account_number VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE name username VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX user_un_email_username ON user (email, username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          account
        DROP
          api_endpoint_url,
        CHANGE
          account_number account_number VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX user_un_email_username ON user');
        $this->addSql('ALTER TABLE
          user
        CHANGE
          username name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
