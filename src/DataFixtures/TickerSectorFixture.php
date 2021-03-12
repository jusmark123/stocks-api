<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Entity\UserConstants;
use App\Entity\Factory\TickerSectorFactory;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TickerSectorFixture.
 */
class TickerSectorFixture extends AbstractDataFixture
{
    const REFERENCE_ID = 'reference_id';
    const NAME = 'name';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $item) {
            $sector = TickerSectorFactory::init()
                ->setName($item[self::NAME])
                ->setCreatedBy(UserConstants::SYSTEM_USER_USERNAME)
                ->setModifiedBy(UserConstants::SYSTEM_USER_USERNAME);

            $manager->persist($sector);
            $this->setReference('sector_'.$item[self::REFERENCE_ID], $sector);
        }
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }

    /**
     * @return \string[][]
     */
    protected function getData(): array
    {
        return [
            [
                self::NAME => 'Technology',
                self::REFERENCE_ID => 'technology',
            ],
            [
                self::NAME => 'Communication Services',
                self::REFERENCE_ID => 'comms',
            ],
            [
                self::NAME => 'Consumer Cyclical',
                self::REFERENCE_ID => 'consumer',
            ],
            [
                self::NAME => 'Industrials',
                self::REFERENCE_ID => 'industry',
            ],
            [
                self::NAME => 'Healthcare',
                self::REFERENCE_ID => 'health',
            ],
            [
                self::NAME => 'Financial Services',
                self::REFERENCE_ID => 'finance',
            ],
            [
                self::NAME => 'Consumer Defensive',
                self::REFERENCE_ID => 'defense',
            ],
            [
                self::NAME => 'Basic Materials',
                self::REFERENCE_ID => 'basic',
            ],
            [
                self::NAME => 'Energy',
                self::REFERENCE_ID => 'energy',
            ],
            [
                self::NAME => 'Real Estate',
                self::REFERENCE_ID => 'reit',
            ],
            [
                self::NAME => 'Utilities',
                self::REFERENCE_ID => 'utilities',
            ],
        ];
    }
}
