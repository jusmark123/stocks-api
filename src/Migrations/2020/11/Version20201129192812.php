<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Constants\Entity\AccountStatusTypeConstants;
use App\Constants\Entity\SourceTypeConstants;
use App\Constants\Entity\UserTypeConstants;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201129192812 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription(): string
    {
        return 'System Types';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        foreach ($this->getTypes() as $key => $types) {
            foreach ($types as $id => $name) {
                $this->addSql("INSERT INTO `$key` (`id`, `name`, `description`, `deactivated_at`, `created_at`, `modified_at`, `created_by`, `modified_by`)
									VALUES (:id, :name, :description, null, NOW(), NOW(), :system_username, :system_username);",
                    [
                        ':id' => $id,
                        ':name' => $name,
                        ':description' => $name,
                        ':system_username' => 'system',
                    ]);
            }
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }

    public function getTypes(): array
    {
        return [
            'account_status_type' => AccountStatusTypeConstants::getTypes(),
            'source_type' => SourceTypeConstants::getTypes(),
            'user_type' => UserTypeConstants::getTypes(),
        ];
    }
}
