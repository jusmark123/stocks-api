<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210313092805 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Massive Refactoring for ticker/brokerage fixes';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_status_type (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          brokerage_id INT UNSIGNED NOT NULL,
          name VARCHAR(100) NOT NULL,
          description TEXT DEFAULT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          INDEX IDX_AF6A853B79969C22 (brokerage_id),
          UNIQUE INDEX order_status_type_un_name (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE position_log (
          id INT UNSIGNED AUTO_INCREMENT NOT NULL,
          order_id INT UNSIGNED NOT NULL,
          position_id INT UNSIGNED NOT NULL,
          source_id INT UNSIGNED NOT NULL,
          average_filled_price DOUBLE PRECISION NOT NULL,
          change_today DOUBLE PRECISION NOT NULL,
          cost_basis DOUBLE PRECISION NOT NULL,
          current_price DOUBLE PRECISION NOT NULL,
          quantity DOUBLE PRECISION NOT NULL,
          side ENUM(\'long\', \'short\') NOT NULL COMMENT \'(DC2Type:enumSideType)\',
          unrealized_profit DOUBLE PRECISION NOT NULL,
          unrealized_profit_percentage DOUBLE PRECISION NOT NULL,
          created_at DATETIME NOT NULL,
          created_by VARCHAR(255) DEFAULT NULL,
          deactivated_at DATETIME DEFAULT NULL,
          deactivated_by VARCHAR(255) DEFAULT NULL,
          modified_at DATETIME NOT NULL,
          modified_by VARCHAR(255) DEFAULT NULL,
          guid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
          UNIQUE INDEX UNIQ_7B8960DD8D9F6D38 (order_id),
          INDEX IDX_7B8960DDDD842E46 (position_id),
          INDEX IDX_7B8960DD953C1C61 (source_id),
          INDEX position_log_ix_posiiton_source (position_id, source_id),
          INDEX position_log_ix_position_order (position_id, order_id),
          UNIQUE INDEX position_log_un_guid (guid),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source_position (
          source_id INT UNSIGNED NOT NULL,
          position_id INT UNSIGNED NOT NULL,
          INDEX IDX_50CD227953C1C61 (source_id),
          INDEX IDX_50CD227DD842E46 (position_id),
          PRIMARY KEY(source_id, position_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          order_status_type
        ADD
          CONSTRAINT FK_AF6A853B79969C22 FOREIGN KEY (brokerage_id) REFERENCES brokerage (id)');
        $this->addSql('ALTER TABLE
          position_log
        ADD
          CONSTRAINT FK_7B8960DD8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE
          position_log
        ADD
          CONSTRAINT FK_7B8960DDDD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('ALTER TABLE
          position_log
        ADD
          CONSTRAINT FK_7B8960DD953C1C61 FOREIGN KEY (source_id) REFERENCES source (id)');
        $this->addSql('ALTER TABLE
          source_position
        ADD
          CONSTRAINT FK_50CD227953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          source_position
        ADD
          CONSTRAINT FK_50CD227DD842E46 FOREIGN KEY (position_id) REFERENCES position (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          order_status_type_id INT UNSIGNED DEFAULT NULL,
        ADD
          position_log_id INT UNSIGNED NOT NULL,
        ADD
          amount_usd DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT FK_F5299398A5EC2BA6 FOREIGN KEY (order_status_type_id) REFERENCES order_status_type (id)');
        $this->addSql('ALTER TABLE
          `order`
        ADD
          CONSTRAINT FK_F52993981289330B FOREIGN KEY (position_log_id) REFERENCES position_log (id)');
        $this->addSql('CREATE INDEX IDX_F5299398A5EC2BA6 ON `order` (order_status_type_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52993981289330B ON `order` (position_log_id)');
        $this->addSql('ALTER TABLE
          order_log
        ADD
          order_status_id INT UNSIGNED NOT NULL,
        ADD
          amount_usd DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
        ADD
          event LONGTEXT NOT NULL,
        ADD
          timestamp DATETIME NOT NULL,
        DROP
          order_type');
        $this->addSql('ALTER TABLE
          order_log
        ADD
          CONSTRAINT FK_CC6427A5D7707B45 FOREIGN KEY (order_status_id) REFERENCES order_status_type (id)');
        $this->addSql('CREATE INDEX IDX_CC6427A5D7707B45 ON order_log (order_status_id)');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F5953C1C61');
        $this->addSql('DROP INDEX IDX_462CE4F5953C1C61 ON position');
        $this->addSql('DROP INDEX position_un_source_type ON position');
        $this->addSql('ALTER TABLE
          position
        ADD
          avg_filled_price DOUBLE PRECISION DEFAULT \'0\' NOT NULL,
        DROP
          source_id,
        DROP
          side,
        DROP
          source_class');
        $this->addSql('CREATE INDEX position_ix_account_ticker ON position (account_id, ticker_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A5EC2BA6');
        $this->addSql('ALTER TABLE order_log DROP FOREIGN KEY FK_CC6427A5D7707B45');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993981289330B');
        $this->addSql('DROP TABLE order_status_type');
        $this->addSql('DROP TABLE position_log');
        $this->addSql('DROP TABLE source_position');
        $this->addSql('DROP INDEX IDX_F5299398A5EC2BA6 ON `order`');
        $this->addSql('DROP INDEX UNIQ_F52993981289330B ON `order`');
        $this->addSql('ALTER TABLE `order` DROP order_status_type_id, DROP position_log_id, DROP amount_usd');
        $this->addSql('DROP INDEX IDX_CC6427A5D7707B45 ON order_log');
        $this->addSql('ALTER TABLE
          order_log
        ADD
          order_type ENUM(
            \'limit\', \'market\', \'stop\', \'stop_limit\',
            \'trailing_stop\'
          ) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:enumOrderType)\',
        DROP
          order_status_id,
        DROP
          amount_usd,
        DROP
          event,
        DROP
          timestamp');
        $this->addSql('DROP INDEX position_ix_account_ticker ON position');
        $this->addSql('ALTER TABLE
          position
        ADD
          source_id INT UNSIGNED NOT NULL,
        ADD
          side ENUM(\'long\', \'short\') CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:enumSideType)\',
        ADD
          source_class VARCHAR(125) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
        DROP
          avg_filled_price');
        $this->addSql('ALTER TABLE
          position
        ADD
          CONSTRAINT FK_462CE4F5953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_462CE4F5953C1C61 ON position (source_id)');
        $this->addSql('CREATE UNIQUE INDEX position_un_source_type ON position (source_id, source_class, ticker_id)');
    }
}
