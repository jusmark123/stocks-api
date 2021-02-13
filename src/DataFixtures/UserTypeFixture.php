<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Entity\UserTypeConstants;
use App\Entity\UserType;
use Doctrine\Persistence\ObjectManager;

class UserTypeFixture extends AbstractDataFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (UserTypeConstants::NAMES as $key => $description) {
            $userType = (new UserType())
                ->setName($description)
                ->setDescription($description);

            $manager->persist($userType);

            $this->setReference(sprintf('userType_%d', $key), $userType);
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
