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
final class Version20201214000339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_status_type ADD brokerage_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE
          order_status_type
        ADD
          CONSTRAINT FK_AF6A853B79969C22 FOREIGN KEY (brokerage_id) REFERENCES brokerage (id)');
        $this->addSql('CREATE INDEX IDX_AF6A853B79969C22 ON order_status_type (brokerage_id)');
        $this->addSql('ALTER TABLE order_type ADD brokerage_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE
          order_type
        ADD
          CONSTRAINT FK_C12F6D3E79969C22 FOREIGN KEY (brokerage_id) REFERENCES brokerage (id)');
        $this->addSql('CREATE INDEX IDX_C12F6D3E79969C22 ON order_type (brokerage_id)');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY position_fk_position_side_type');
        $this->addSql('DROP INDEX IDX_462CE4F5101D35F9 ON position');
        $this->addSql('ALTER TABLE position ADD side VARCHAR(255) NOT NULL, DROP position_side_type_id');
        $this->addSql('ALTER TABLE position_side_type ADD brokerage_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE
          position_side_type
        ADD
          CONSTRAINT FK_85BB776379969C22 FOREIGN KEY (brokerage_id) REFERENCES brokerage (id)');
        $this->addSql('CREATE INDEX IDX_85BB776379969C22 ON position_side_type (brokerage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_status_type DROP FOREIGN KEY FK_AF6A853B79969C22');
        $this->addSql('DROP INDEX IDX_AF6A853B79969C22 ON order_status_type');
        $this->addSql('ALTER TABLE order_status_type DROP brokerage_id');
        $this->addSql('ALTER TABLE order_type DROP FOREIGN KEY FK_C12F6D3E79969C22');
        $this->addSql('DROP INDEX IDX_C12F6D3E79969C22 ON order_type');
        $this->addSql('ALTER TABLE order_type DROP brokerage_id');
        $this->addSql('ALTER TABLE position ADD position_side_type_id INT UNSIGNED NOT NULL, DROP side');
        $this->addSql('ALTER TABLE
          position
        ADD
          CONSTRAINT position_fk_position_side_type FOREIGN KEY (position_side_type_id) REFERENCES position_side_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_462CE4F5101D35F9 ON position (position_side_type_id)');
        $this->addSql('ALTER TABLE position_side_type DROP FOREIGN KEY FK_85BB776379969C22');
        $this->addSql('DROP INDEX IDX_85BB776379969C22 ON position_side_type');
        $this->addSql('ALTER TABLE position_side_type DROP brokerage_id');
    }
}
