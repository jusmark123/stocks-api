<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210101184811 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticker DROP INDEX ticker_un_name, ADD INDEX ticker_ix_name (name)');
        $this->addSql('CREATE UNIQUE INDEX ticker_un_ticker ON ticker (ticker)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticker DROP INDEX ticker_ix_name, ADD UNIQUE INDEX ticker_un_name (name)');
        $this->addSql('DROP INDEX ticker_un_ticker ON ticker');
    }
}
