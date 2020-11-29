<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Brokerage\AlpacaConstants;
use App\Entity\Account;
use App\Entity\AccountStatusType;
use App\Entity\Brokerage;
use App\Entity\Manager\AccountStatusTypeEntityManager;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AppFixtures extends Fixture
{
    private $accountStatusTypeManager;

    public function __construct(
            AccountStatusTypeEntityManager $accountStatusTypeEntityManager
        ) {
        $this->accountStatusTypeManager = $accountStatusTypeEntityManager;
    }

    public function load(ObjectManager $manager)
    {
        $accountStatuses = $this->getAccountStatusTypes();

        foreach ($accountStatuses as $key => $status) {
            $accountStatus = (new AccountStatusType())
              ->setName($status['name'])
              ->setDescription($status['description']);
            $accountStatuses[$key] = $accountStatus;
            $manager->persist($accountStatus);
        }

        // Users
        $systemUser = (new User())
            ->setGuid(Uuid::fromString('18862da9-a1da-4106-91ee-afc0b98efe71'))
            ->setUsername('system-user')
            ->setEmail('system-user@stockapi.com')
            ->setDescription('default system user');

        $manager->persist($systemUser);

        // Alpaca Markets
        $alpacaBrokerage = (new Brokerage())
          ->setGuid(Uuid::fromString('9e13594c-0172-45b4-a9db-ed11db638601'))
          ->setName(AlpacaConstants::BROKERAGE_NAME)
					->setContext('alpaca')
          ->setDescription('Alpaca Trade Api')
          ->setUrl('https://alpaca.markets/')
          ->setApiDocumentUrl('https://alpaca.markets/docs/api-documentation/api-v2/');

        $manager->persist($alpacaBrokerage);

        $alpacaPaperAccount = (new Account())
          ->setGuid(Uuid::fromString('3347b24d-2984-449a-9bde-ccaa5f81946c'))
          ->setAccountStatusType($accountStatuses[0])
          ->setApiKey('PKU2P6NISHZELU5ATWEQ')
          ->setApiSecret('cJALUudu/mZX50JXicten5NeHIDTGIlKmPDyPz9v')
          ->setApiEndpointUrl('https://paper-api.alpaca.markets')
          ->setBrokerage($alpacaBrokerage)
          ->setDescription('Alpaca paper trading account')
          ->setName('alpaca-paper')
          ->addUser($systemUser);

        $manager->persist($alpacaPaperAccount);

        $manager->flush();
    }

    private function getAccountStatusTypes()
    {
        return [
                [
                    'name' => AccountStatusType::ACTIVE,
                    'description' => 'Active account',
                ],
                [
                    'name' => AccountStatusType::INACTIVE,
                    'description' => 'Inactive account',
                ],
            ];
    }
}
