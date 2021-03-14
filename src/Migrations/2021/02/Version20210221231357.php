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
final class Version20210221231357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Updated AlpacaPosition/Source/AlpacaOrder relations and added OrderLog entity for improved interaction';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('SET FOREIGN_KEY_CHECKS=0;');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_order_status_type');
        $this->addSql('CREATE TABLE order_log (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          order_id INT UNSIGNED NOT NULL,
          order_type ENUM(
            \'limit\', \'market\', \'stop\', \'stop_limit\',
            \'trailing_stop\'
          ) NOT NULL COMMENT \'(DC2Type:enumOrderType)\',
          quantity DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
          side ENUM(\'long\', \'short\') NOT NULL COMMENT \'(DC2Type:enumSideType)\',
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          INDEX order_log_ix_order_id (order_id),
          UNIQUE INDEX order_log_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          order_log
        ADD
          CONSTRAINT FK_CC6427A58D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('DROP TABLE order_status_type');
        $this->addSql('DROP TABLE position_side_type');
        $this->addSql('DROP TABLE stream');
        $this->addSql('ALTER TABLE
          account
        ADD
          is_paper_account TINYINT(1) DEFAULT \'0\' NOT NULL,
        DROP
          api_endpoint_url');
        $this->addSql('ALTER TABLE algorithm DROP filename');
        $this->addSql('ALTER TABLE brokerage ADD has_paper_accounts TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_brokerage');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_user');
        $this->addSql('DROP INDEX IDX_F5299398A5EC2BA6 ON `order`');
        $this->addSql('DROP INDEX IDX_F5299398A76ED395 ON `order`');
        $this->addSql('DROP INDEX order_ix_broker_id ON `order`');
        $this->addSql('DROP INDEX order_ix_broker_order_id ON `order`');
        $this->addSql('DROP INDEX order_un_broker_order_id_brokerage ON `order`');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          ticker_id INT UNSIGNED NOT NULL,
        ADD
          quantity DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
        ADD
          quantity_filled DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
        DROP
          brokerage_id,
        DROP
          user_id,
        DROP
          order_status_type_id,
        DROP
          qty,
        DROP
          broker_order_id,
        DROP
          amount_usd,
        DROP
          symbol,
        CHANGE
          position_id position_id INT UNSIGNED NOT NULL,
        CHANGE
          side side ENUM(\'long\', \'short\') NOT NULL COMMENT \'(DC2Type:enumSideType)\',
        CHANGE
          fees filled_average_price DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT FK_F5299398556B180E FOREIGN KEY (ticker_id) REFERENCES ticker (id)');
        $this->addSql('CREATE INDEX IDX_F5299398556B180E ON `order` (ticker_id)');
        $this->addSql('ALTER TABLE
          position
        ADD
          account_id INT UNSIGNED NOT NULL,
        ADD
          ticker_id INT UNSIGNED NOT NULL,
        ADD
          type ENUM(
            \'crypto\', \'currency\', \'equity\', \'index\',
            \'option\'
          ) NOT NULL COMMENT \'(DC2Type:enumPositionType)\',
        ADD
          source_class VARCHAR(125) NOT NULL,
        DROP
          symbol,
        CHANGE
          quantity quantity DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
        CHANGE
          side side ENUM(\'long\', \'short\') NOT NULL COMMENT \'(DC2Type:enumSideType)\'');
        $this->addSql('ALTER TABLE
          position
        ADD
          CONSTRAINT FK_462CE4F59B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE
          position
        ADD
          CONSTRAINT FK_462CE4F5556B180E FOREIGN KEY (ticker_id) REFERENCES ticker (id)');
        $this->addSql('CREATE INDEX IDX_462CE4F59B6B5FBA ON position (account_id)');
        $this->addSql('CREATE INDEX IDX_462CE4F5556B180E ON position (ticker_id)');
        $this->addSql('CREATE UNIQUE INDEX position_un_source_type ON position (source_id, source_class, ticker_id)');
        $this->addSql('ALTER TABLE position RENAME INDEX position_ix_source_id TO IDX_462CE4F5953C1C61');
        $this->addSql('ALTER TABLE user ADD source_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE
          user
        ADD
          CONSTRAINT FK_8D93D649953C1C61 FOREIGN KEY (source_id) REFERENCES source (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649953C1C61 ON user (source_id)');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('SET FOREIGN_KEY_CHECKS=0;');
        $this->addSql('CREATE TABLE order_status_type (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          brokerage_id INT UNSIGNED NOT NULL,
          name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
          description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          modified_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          INDEX IDX_AF6A853B79969C22 (brokerage_id),
          UNIQUE INDEX order_status_type_un_name (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\'');
        $this->addSql('CREATE TABLE position_side_type (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          brokerage_id INT UNSIGNED NOT NULL,
          name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
          description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          modified_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          INDEX IDX_85BB776379969C22 (brokerage_id),
          UNIQUE INDEX position_side_type_un_name (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\'');
        $this->addSql('CREATE TABLE stream (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          description TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          modified_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
          guid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\',
          UNIQUE INDEX stream_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\'');
        $this->addSql('ALTER TABLE
          order_status_type
        ADD
          CONSTRAINT FK_AF6A853B79969C22 FOREIGN KEY (brokerage_id) REFERENCES brokerage (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE
          position_side_type
        ADD
          CONSTRAINT FK_85BB776379969C22 FOREIGN KEY (brokerage_id) REFERENCES brokerage (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE order_log');
        $this->addSql('ALTER TABLE
          account
        ADD
          api_endpoint_url VARCHAR(150) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        DROP
          is_paper_account');
        $this->addSql('ALTER TABLE
          algorithm
        ADD
          filename VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE brokerage DROP has_paper_accounts');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398556B180E');
        $this->addSql('DROP INDEX IDX_F5299398556B180E ON `order`');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          user_id INT UNSIGNED NOT NULL,
        ADD
          order_status_type_id INT UNSIGNED DEFAULT NULL,
        ADD
          qty INT DEFAULT 0 NOT NULL,
        ADD
          fees DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
        ADD
          broker_order_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        ADD
          amount_usd DOUBLE PRECISION NOT NULL,
        ADD
          symbol VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        DROP
          filled_average_price,
        DROP
          quantity,
        DROP
          quantity_filled,
        CHANGE
          position_id position_id INT UNSIGNED DEFAULT NULL,
        CHANGE
          side side VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        CHANGE
          ticker_id brokerage_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_brokerage FOREIGN KEY (brokerage_id) REFERENCES brokerage (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_order_status_type FOREIGN KEY (order_status_type_id) REFERENCES order_status_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_user FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F5299398A5EC2BA6 ON `order` (order_status_type_id)');
        $this->addSql('CREATE INDEX IDX_F5299398A76ED395 ON `order` (user_id)');
        $this->addSql('CREATE INDEX order_ix_broker_id ON `order` (brokerage_id)');
        $this->addSql('CREATE INDEX order_ix_broker_order_id ON `order` (broker_order_id)');
        $this->addSql('CREATE UNIQUE INDEX order_un_broker_order_id_brokerage ON `order` (broker_order_id, brokerage_id)');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F59B6B5FBA');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F5556B180E');
        $this->addSql('DROP INDEX IDX_462CE4F59B6B5FBA ON position');
        $this->addSql('DROP INDEX IDX_462CE4F5556B180E ON position');
        $this->addSql('DROP INDEX position_un_source_type ON position');
        $this->addSql('ALTER TABLE
          position
        ADD
          symbol VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        DROP
          account_id,
        DROP
          ticker_id,
        DROP
          type,
        DROP
          source_class,
        CHANGE
          quantity quantity INT DEFAULT 0 NOT NULL,
        CHANGE
          side side VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE position RENAME INDEX idx_462ce4f5953c1c61 TO position_ix_source_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649953C1C61');
        $this->addSql('DROP INDEX IDX_8D93D649953C1C61 ON user');
        $this->addSql('ALTER TABLE user DROP source_id');
        $this->addSql('SET FOREIGN_KEY_CHECKS=1;');
    }
}
