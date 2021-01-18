<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Entity\SourceConstants;
use App\Constants\Entity\SourceTypeConstants;
use App\Entity\Factory\SourceFactory;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

/**
 * Class SourceFixture.
 */
class SourceFixture extends AbstractDataFixture
{
    const SOURCE_TYPE = 'source_type';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $item) {
            $source = SourceFactory::create()
                ->setGuid(Uuid::fromString($item[self::GUID]))
                ->setName($item[self::NAME])
                ->setDescription($item[self::DESCRIPTION])
                ->setSourceType($item[self::SOURCE_TYPE]);

            $manager->persist($source);
            $this->setReference($item[self::REFERENCE_ID], $source);
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
                self::REFERENCE_ID => 'source_1',
                self::GUID => SourceConstants::SYSTEM_SOURCE_GUID,
                self::NAME => SourceConstants::SYSTEM_SOURCE_USERNAME,
                self::DESCRIPTION => SourceConstants::SYSTEM_SOURCE_DESCRIPTION,
                self::SOURCE_TYPE => $this->getReference(
                    sprintf('sourceType_%d', SourceTypeConstants::SYSTEM)
                ),
            ],
            [
                self::REFERENCE_ID => 'source_2',
                self::GUID => '5aac6583-9ebc-4f59-bbe6-555c302dc00d',
                self::NAME => 'Python MACD Momentum Trader',
                self::DESCRIPTION => 'AI trading algorithm using MACD',
                self::SOURCE_TYPE => $this->getReference(
                    sprintf('sourceType_%d', SourceTypeConstants::ALGORITHM)
                ),
            ],
        ];
    }
}
