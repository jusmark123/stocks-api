<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Entity\SourceTypeConstants;
use App\Entity\SourceType;
use Doctrine\Persistence\ObjectManager;

/**
 * Class SourceTypeFixture.
 */
class SourceTypeFixture extends AbstractDataFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (SourceTypeConstants::NAMES as $key => $description) {
            $sourceType = (new SourceType())
                ->setName($description)
                ->setDescription($description);

            $manager->persist($sourceType);

            $this->setReference(sprintf('sourceType_%d', $key), $sourceType);
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
