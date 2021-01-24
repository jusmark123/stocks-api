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
final class Version20210124153431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Altering the user table to add the avatar field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD COLUMN avatar VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP COLUMN avatar');
    }
}
