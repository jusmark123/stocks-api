<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Entity\UserConstants;
use App\Constants\Entity\UserTypeConstants;
use App\Entity\Factory\UserFactory;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

/**
 * Class UserFixture.
 */
class UserFixture extends AbstractDataFixture
{
    const PASSWORD = 'password';
    const USERNAME = 'username';
    const EMAIL = 'email';
    const USER_TYPE = 'user_type';
    const SOURCE = 'source';
    const ROLES = 'roles';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $item) {
            $user = UserFactory::create()
                ->setGuid(Uuid::fromString($item[self::GUID]))
                ->setUsername($item[self::USERNAME])
                ->setPassword($item[self::PASSWORD])
                ->setEmail($item[self::EMAIL])
                ->setDescription($item[self::DESCRIPTION])
                ->setUserType($item[self::USER_TYPE])
                ->setRoles($item[self::ROLES]);

            $manager->persist($user);

            $this->setReference($item[self::REFERENCE_ID], $user);
        }
        $manager->flush();
    }

    /**
     * @return int|void
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        return [
            [
                self::REFERENCE_ID => UserConstants::SYSTEM_USER_USERNAME,
                self::GUID => UserConstants::SYSTEM_USER_GUID,
                self::USERNAME => UserConstants::SYSTEM_USER_USERNAME,
                self::EMAIL => UserConstants::SYSTEM_USER_EMAIL,
                self::PASSWORD => null,
                self::DESCRIPTION => UserConstants::SYSTEM_USER_DESCRIPTION,
                self::USER_TYPE => $this->getReference(sprintf('userType_%d', UserTypeConstants::SYSTEM_ADMIN)),
                self::ROLES => ['ROLE_SERVICE_ACCOUNT'],
            ],
            [
                self::REFERENCE_ID => 'bucho',
                self::GUID => Uuid::uuid1()->toString(),
                self::EMAIL => 'mjhinkson@gmail.com',
                self::USERNAME => 'bucho',
                self::DESCRIPTION => 'User Account',
                self::PASSWORD => '$argon2id$v=19$m=65536,t=4,p=1$p+SQMWymhPgjYOKyqXG8Jw$J8BLPxwv0JtIFGL7Aa153tHPK2t9KkaIVrvAfLLvh9Q',
                self::USER_TYPE => $this->getReference(sprintf('userType_%d', UserTypeConstants::ACCOUNT_USER)),
                self::ROLES => ['ROLE_USER'],
            ],
            [
                self::REFERENCE_ID => 'justin',
                self::GUID => Uuid::uuid1()->toString(),
                self::USERNAME => 'justin',
                self::EMAIL => 'jusmark123@yahoo.com',
                self::PASSWORD => '$argon2id$v=19$m=65536,t=4,p=1$vb2mzT+5QrH7Smwhpz/kiQ$iEYgruhmO7Bq/ftMzgqrJltopsnsJ1D725T8HtCt4kw',
                self::DESCRIPTION => 'User Account',
                self::USER_TYPE => $this->getReference(sprintf('userType_%d', UserTypeConstants::ACCOUNT_USER)),
                self::ROLES => ['ROLE_USER'],
            ],
        ];
    }
}
