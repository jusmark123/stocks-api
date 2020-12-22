<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Brokerage\AlpacaConstants;
use App\Constants\Entity\AccountStatusTypeConstants;
use App\Constants\Entity\SourceConstants;
use App\Constants\Entity\SourceTypeConstants;
use App\Constants\Entity\UserConstants;
use App\Constants\Entity\UserTypeConstants;
use App\Entity\AccountStatusType;
use App\Entity\Factory\AccountFactory;
use App\Entity\Factory\BrokerageFactory;
use App\Entity\Factory\SourceFactory;
use App\Entity\Factory\UserFactory;
use App\Entity\Manager\AccountStatusTypeEntityManager;
use App\Entity\Source;
use App\Entity\SourceType;
use App\Entity\UserType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AppFixtures extends Fixture
{
    /** @var AccountStatusTypeEntityManager */
    private $accountStatusTypeManager;

    public function __construct(AccountStatusTypeEntityManager $accountStatusTypeManager)
    {
        $this->accountStatusTypeManager = $accountStatusTypeManager;
    }

    public function load(ObjectManager $manager)
    {
        // Users
        /** @var UserType $userType */
        $userType = $manager->getRepository(UserType::class)->find(UserTypeConstants::SERVICE_ACCOUNT);
        $systemUser = UserFactory::create()
            ->setGuid(Uuid::fromString(UserConstants::SYSTEM_USER_GUID))
            ->setUsername(UserConstants::SYSTEM_USER_USERNAME)
            ->setEmail(UserConstants::SYSTEM_USER_EMAIL)
            ->setDescription(UserConstants::SYSTEM_USER_DESCRIPTION)
            ->setUserType($userType);

        $manager->persist($systemUser);

        // Source
        $sourceType = $manager->getRepository(SourceType::class)->find(SourceTypeConstants::SYSTEM);
        $systemSource = SourceFactory::create()
            ->setGuid(Uuid::fromString(SourceConstants::SYSTEM_SOURCE_GUID))
            ->setName(SourceConstants::SYSTEM_SOURCE_USERNAME)
            ->setDescription(SourceConstants::SYSTEM_SOURCE_DESCRIPTION)
            ->setSourceType($sourceType);

        $manager->persist($systemSource);

        // Alpaca Markets
        $alpacaBrokerage = BrokerageFactory::create()
          ->setGuid(Uuid::fromString('9e13594c-0172-45b4-a9db-ed11db638601'))
          ->setName(AlpacaConstants::BROKERAGE_NAME)
                    ->setContext('alpaca')
          ->setDescription('Alpaca Trade Api')
          ->setUrl('https://alpaca.markets/')
          ->setApiDocumentUrl('https://alpaca.markets/docs/api-documentation/api-v2/');

        $manager->persist($alpacaBrokerage);

        /** @var AccountStatusType $accountStatusType */
        $accountStatusType = $manager->getRepository(AccountStatusType::class)->find(AccountStatusTypeConstants::ACTIVE);
        $alpacaPaperAccount = AccountFactory::create()
          ->setGuid(Uuid::fromString('3347b24d-2984-449a-9bde-ccaa5f81946c'))
          ->setAccountStatusType($accountStatusType)
          ->setApiKey('PKU2P6NISHZELU5ATWEQ')
          ->setApiSecret('yACk0pUFDlBQRBrbktzTe5iODUumrQACriY7TSK3')
          ->setApiEndpointUrl('https://paper-api.alpaca.markets')
          ->setBrokerage($alpacaBrokerage)
          ->setDescription('Alpaca paper trading account')
          ->setName('alpaca-paper')
          ->addUser($systemUser);

        $manager->persist($alpacaPaperAccount);

        //MomentumTraderAlgorithm Source
        /** @var SourceType $sourceType */
        $sourceType = $manager->getRepository(SourceType::class)->find(SourceTypeConstants::ALGORITHM);
        $momentumTrader = SourceFactory::create()
            ->setGuid(Uuid::fromString('5aac6583-9ebc-4f59-bbe6-555c302dc00d'))
            ->setName('Python MACD Momentum Trader')
            ->setDescription('AI trading algorithm using MACD')
            ->setSourceType($sourceType);

        $manager->persist($momentumTrader);

        $manager->flush();
    }
}
