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
final class Version20201204160412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added additional fields to order table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          `order`
        ADD
          qty INT DEFAULT 0 NOT NULL AFTER user_id,
        ADD
          avg_cost DOUBLE PRECISION DEFAULT \'0\' NOT NULL AFTER qty,
        ADD
          fees DOUBLE PRECISION DEFAULT \'0\' NOT NULL AFTER avg_cost,
        ADD
          side VARCHAR(5) NOT NULL AFTER fees');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP qty, DROP avg_cost, DROP fees, DROP side');
    }
}
