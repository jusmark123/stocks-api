<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131125950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Change the avatar column in the user table from a varchar to a blob';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE user CHANGE COLUMN avatar avatar BLOB');

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE user CHANGE COLUMN avatar avatar VARCHAR(255) DEFAULT NULL');

    }
}
