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
final class Version20201127061700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brokerage CHANGE api_documenation_url api_document_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE INDEX order_ix_broker_order_id ON `order` (broker_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          brokerage
        CHANGE
          api_document_url api_documenation_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX order_ix_broker_order_id ON `order`');
    }
}
