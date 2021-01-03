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
final class Version20201215045539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD side VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE brokerage_ticker DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE brokerage_ticker ADD PRIMARY KEY (ticker_id, brokerage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brokerage_ticker DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE brokerage_ticker ADD PRIMARY KEY (brokerage_id, ticker_id)');
        $this->addSql('ALTER TABLE `order` DROP side');
    }
}
