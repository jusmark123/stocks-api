<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Entity\AccountStatusTypeConstants;
use App\Entity\AccountStatusType;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AccountStatusTypeFixture.
 */
class AccountStatusTypeFixture extends AbstractDataFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (AccountStatusTypeConstants::NAMES as $key => $description) {
            $accountStatusType = (new AccountStatusType())
                ->setName($description)
                ->setDescription($description);

            $manager->persist($accountStatusType);

            $this->setReference(sprintf('accountStatusType_%d', $key), $accountStatusType);
        }

        $manager->flush();
    }

    /**
     * @return int|void
     */
    public function getOrder()
    {
        return 0;
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        return [];
    }
}
