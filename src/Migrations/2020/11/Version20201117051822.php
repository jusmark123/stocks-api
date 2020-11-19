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
final class Version20201117051822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial Schema Migration';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (
					guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          brokerage_id INT UNSIGNED NOT NULL,
          account_status_type_id INT UNSIGNED NOT NULL,
          account_number VARCHAR(20) NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          api_key VARCHAR(100) NOT NULL,
          api_secret VARCHAR(100) NOT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          INDEX IDX_7D3656A479969C22 (brokerage_id),
          INDEX account_ix_account_status_type_id (account_status_type_id),
          UNIQUE INDEX account_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE account_users (
          account_id INT UNSIGNED NOT NULL,
          user_id INT UNSIGNED NOT NULL,
          INDEX IDX_220C7C989B6B5FBA (account_id),
          INDEX IDX_220C7C98A76ED395 (user_id),
          PRIMARY KEY(account_id, user_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE account_status_type (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          UNIQUE INDEX account_status_type_un_name (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brokerage (
					guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          url VARCHAR(255) NOT NULL,
          api_documenation_url VARCHAR(255) DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          UNIQUE INDEX brokerage_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (
					guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          brokerage_id INT UNSIGNED DEFAULT NULL,
          account_id INT UNSIGNED DEFAULT NULL,
          postion_id INT UNSIGNED DEFAULT NULL,
          source_id INT UNSIGNED NOT NULL,
          user_id INT UNSIGNED NOT NULL,
          order_type_id INT UNSIGNED DEFAULT NULL,
          order_status_type_id INT UNSIGNED DEFAULT NULL,
          broker_order_id VARCHAR(255) NOT NULL,
          amount_usd DOUBLE PRECISION NOT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          INDEX IDX_F529939879969C22 (brokerage_id),
          INDEX IDX_F52993989B6B5FBA (account_id),
          INDEX IDX_F5299398DA81E3CC (postion_id),
          INDEX IDX_F5299398953C1C61 (source_id),
          INDEX IDX_F5299398A76ED395 (user_id),
          INDEX IDX_F5299398333625D8 (order_type_id),
          INDEX IDX_F5299398A5EC2BA6 (order_status_type_id),
          INDEX order_ix_broker_order_id (broker_order_id),
          UNIQUE INDEX order_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_status_type (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          UNIQUE INDEX order_status_type_un_name (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_type (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          UNIQUE INDEX order_type_un_name (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE position (
					guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          account_id INT UNSIGNED NOT NULL,
          position_side_type_id INT UNSIGNED NOT NULL,
          symbol VARCHAR(20) NOT NULL,
          quantity INT DEFAULT 0 NOT NULL,
          exchange VARCHAR(255) NOT NULL,
          market_value DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
          change_today DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
          cost_basis DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          INDEX IDX_462CE4F59B6B5FBA (account_id),
          INDEX IDX_462CE4F5101D35F9 (position_side_type_id),
          INDEX position_ix_symbol (symbol),
          UNIQUE INDEX position_un_guid (guid),
          UNIQUE INDEX position_un_symbol_account_id (symbol, account_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE position_side_type (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          UNIQUE INDEX position_side_type_un_name (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source (
					guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          source_type_id INT UNSIGNED NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          INDEX IDX_5F8A7F738C9334FB (source_type_id),
          UNIQUE INDEX source_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source_type (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          UNIQUE INDEX source_type_un_name (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (
					guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          name VARCHAR(100) NOT NULL,
          first_name VARCHAR(100) DEFAULT NULL,
          last_name VARCHAR(100) DEFAULT NULL,
          email VARCHAR(255) NOT NULL,
          phone VARCHAR(10) NOT NULL,
          description TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          UNIQUE INDEX user_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          account
        ADD
          CONSTRAINT account_fk_brokerage FOREIGN KEY (brokerage_id) REFERENCES brokerage (id)');
        $this->addSql('ALTER TABLE
          account
        ADD
          CONSTRAINT account_fk_account_status_type FOREIGN KEY (account_status_type_id) REFERENCES account_status_type (id)');
        $this->addSql('ALTER TABLE
          account_users
        ADD
          CONSTRAINT account_user_fk_account FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE
          account_users
        ADD
          CONSTRAINT account_user_fk_user FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_brokerage FOREIGN KEY (brokerage_id) REFERENCES brokerage (id)');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_account FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_position FOREIGN KEY (postion_id) REFERENCES position (id)');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_source FOREIGN KEY (source_id) REFERENCES source (id)');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_user FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_order_type FOREIGN KEY (order_type_id) REFERENCES order_type (id)');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT order_fk_order_status_type FOREIGN KEY (order_status_type_id) REFERENCES order_status_type (id)');
        $this->addSql('ALTER TABLE
          position
        ADD
          CONSTRAINT position_fk_account FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE
          position
        ADD
          CONSTRAINT position_fk_position_side_type FOREIGN KEY (position_side_type_id) REFERENCES position_side_type (id)');
        $this->addSql('ALTER TABLE
          source
        ADD
          CONSTRAINT source_fk_source_type FOREIGN KEY (source_type_id) REFERENCES source_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account_users DROP FOREIGN KEY account_user_fk_account');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_brokerage');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY position_fk_account');
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY account_fk_brokerage');
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY account_fk_account_status_type');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_account');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_position');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_source');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_user');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY position_fk_position_side_type');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_order_type');
        $this->addSql('ALTER TABLE source DROP FOREIGN KEY source_fk_source_type');
        $this->addSql('ALTER TABLE account_users DROP FOREIGN KEY account_user_fk_user');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY order_fk_order_status_type');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE account_users');
        $this->addSql('DROP TABLE account_status_type');
        $this->addSql('DROP TABLE brokerage');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_status_type');
        $this->addSql('DROP TABLE order_type');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP TABLE position_side_type');
        $this->addSql('DROP TABLE source');
        $this->addSql('DROP TABLE source_type');
        $this->addSql('DROP TABLE user');
    }
}
