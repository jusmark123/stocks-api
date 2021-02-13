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
final class Version20210211021759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY position_fk_account');
        $this->addSql('DROP INDEX position_ix_account_id ON position');
        $this->addSql('ALTER TABLE position DROP account_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position ADD account_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE
          position
        ADD
          CONSTRAINT position_fk_account FOREIGN KEY (account_id) REFERENCES account (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX position_ix_account_id ON position (account_id)');
    }
}
