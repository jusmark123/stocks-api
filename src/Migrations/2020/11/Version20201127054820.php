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
final class Version20201127054820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Enitty relations fix';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_position');
        $this->addSql('DROP INDEX IDX_F5299398DA81E3CC ON `order`');
        $this->addSql('DROP INDEX order_ix_broker_order_id ON `order`');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_account');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_brokerage');
        $this->addSql('ALTER TABLE
          `order`
        CHANGE
          brokerage_id brokerage_id INT UNSIGNED NOT NULL,
        CHANGE
          account_id account_id INT UNSIGNED NOT NULL,
        CHANGE
          postion_id position_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT FK_F5299398DD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('CREATE INDEX IDX_F5299398DD842E46 ON `order` (position_id)');
        $this->addSql('DROP INDEX idx_f52993989b6b5fba ON `order`');
        $this->addSql('CREATE INDEX order_ix_account_id ON `order` (account_id)');
        $this->addSql('DROP INDEX idx_f529939879969c22 ON `order`');
        $this->addSql('CREATE INDEX order_ix_broker_id ON `order` (brokerage_id)');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_account FOREIGN KEY (account_id) REFERENCES account (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_brokerage FOREIGN KEY (brokerage_id) REFERENCES brokerage (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398DD842E46');
        $this->addSql('DROP INDEX IDX_F5299398DD842E46 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939879969C22');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989B6B5FBA');
        $this->addSql('ALTER TABLE
          `order`
        CHANGE
          brokerage_id brokerage_id INT UNSIGNED DEFAULT NULL,
        CHANGE
          account_id account_id INT UNSIGNED DEFAULT NULL,
        CHANGE
          position_id postion_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_position FOREIGN KEY (postion_id) REFERENCES position (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F5299398DA81E3CC ON `order` (postion_id)');
        $this->addSql('CREATE INDEX order_ix_broker_order_id ON `order` (broker_order_id)');
        $this->addSql('DROP INDEX order_ix_broker_id ON `order`');
        $this->addSql('CREATE INDEX IDX_F529939879969C22 ON `order` (brokerage_id)');
        $this->addSql('DROP INDEX order_ix_account_id ON `order`');
        $this->addSql('CREATE INDEX IDX_F52993989B6B5FBA ON `order` (account_id)');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT FK_F529939879969C22 FOREIGN KEY (brokerage_id) REFERENCES brokerage (id)');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT FK_F52993989B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
    }
}
