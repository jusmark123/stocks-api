<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Brokerage\AlpacaConstants;
use App\Constants\Entity\AccountStatusTypeConstants;
use App\Constants\Entity\UserConstants;
use App\Entity\Factory\AccountFactory;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AccountFixture extends AbstractDataFixture
{
    const GUID = 'guid';
    const ACCOUNT_STATUS_TYPE = 'accountStatusType';
    const API_KEY = 'apiKey';
    const API_SECRET = 'apiSecret';
    const API_ENDPOINT_URL = 'ApiEndpointUrl';
    const BROKERAGE = 'brokerage';
    const DEFAULT = 'default';
    const USERS = 'users';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $item) {
            $account = AccountFactory::create()
                ->setGuid(Uuid::fromString($item[self::GUID]))
                ->setApiKey($item[self::API_KEY])
                ->setApiSecret($item[self::API_SECRET])
                ->setApiEndpointUrl($item[self::API_ENDPOINT_URL])
                ->setBrokerage($item[self::BROKERAGE])
                ->setDescription($item[self::DESCRIPTION])
                ->setDefault($item[self::DEFAULT])
                ->setName($item[self::NAME])
                ->setAccountStatusType($item[self::ACCOUNT_STATUS_TYPE]);

            if ($item[self::USERS]) {
                foreach ($item[self::USERS] as $user) {
                    $account->addUser($user);
                }
            }

            $manager->persist($account);

            $this->setReference($item[self::REFERENCE_ID], $account);
        }
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * @return array[]
     */
    protected function getData(): array
    {
        return [
            [
                self::REFERENCE_ID => 'alpaca_paper',
                self::GUID => '3347b24d-2984-449a-9bde-ccaa5f81946c',
                self::ACCOUNT_STATUS_TYPE => $this->getReference(
                    sprintf('accountStatusType_%d', AccountStatusTypeConstants::ACTIVE)),
                self::API_KEY => 'PKU2P6NISHZELU5ATWEQ',
                self::API_SECRET => 'yACk0pUFDlBQRBrbktzTe5iODUumrQACriY7TSK3',
                self::API_ENDPOINT_URL => 'https://paper-api.alpaca.markets',
                self::BROKERAGE => $this->getReference(AlpacaConstants::BROKERAGE_NAME),
                self::DESCRIPTION => 'Alpaca paper trading account',
                self::DEFAULT => true,
                self::NAME => 'alpaca-paper',
                self::USERS => [
                    $this->getReference(UserConstants::SYSTEM_USER_USERNAME),
                ],
            ],
        ];
    }
}
