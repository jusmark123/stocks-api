<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Constants\Brokerage\BrokerageConstants;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210114061113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        foreach (BrokerageConstants::getBrokerages() as $key => $brokerage) {
            foreach ($brokerage as $table => $values) {
                $stmt = 'INSERT INTO %s (`%s`, created_at, modified_at) VALUES ("%s", NOW(), NOW())';
                if (BrokerageConstants::BROKERAGE_KEY === $table) {
                    $stmt = sprintf($stmt, $table, implode('`, `', array_keys($values)),
                        implode('","', array_values($values)));
                    $this->addSql($stmt);
                } else {
                    foreach ($values as $field => $value) {
                        $fields = [
                            BrokerageConstants::BROKERAGE_NAME_KEY => $field,
                            BrokerageConstants::BROKERAGE_DESCRIPTION_KEY => $value,
                            BrokerageConstants::BROKERAGE_KEY.'_id' => $key + 1,
                        ];
                        $this->addSql(sprintf($stmt, $table, implode('`, `', array_keys($fields)),
                            implode('", "', array_values($fields))));
                    }
                }
            }
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
