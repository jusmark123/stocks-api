<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210205133852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added the refresh_tokens table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE `refresh_tokens` (
              `id` int NOT NULL AUTO_INCREMENT,
              `refresh_token` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
              `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
              `valid` datetime NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `UNIQ_9BACE7E1C74F2195` (`refresh_token`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `refresh_tokens`');
    }
}
