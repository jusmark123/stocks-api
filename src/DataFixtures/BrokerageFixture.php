<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Brokerage\AlpacaConstants;
use App\Entity\Factory\BrokerageFactory;
use App\Entity\OrderType;
use App\Entity\PositionSideType;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

/**
 * Class BrokerageFixture.
 */
class BrokerageFixture extends AbstractDataFixture
{
    const CONTEXT = 'context';
    const DESCRIPTION = 'description';
    const ORDER_CLASSES = 'order_class_type';
    const ORDER_STATUSES = 'order_status_type';
    const ORDER_TYPES = 'order_type';
    const POSITION_SIDE_TYPES = 'position_side_type';
    const URL = 'url';
    const API_DOCUMENT_URL = 'api_document_url';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $item) {
            $typeClasses = [
                OrderType::class => $items[self::ORDER_TYPES] ?? [],
                PositionSideType::class => $items[self::POSITION_SIDE_TYPES] ?? [],
            ];

            $brokerage = BrokerageFactory::create()
                ->setGuid(Uuid::fromString($item[self::GUID]))
                ->setName($item[self::NAME])
                ->setContext($item[self::CONTEXT])
                ->setDescription($item[self::DESCRIPTION])
                ->setUrl($item[self::URL])
                ->setApiDocumentUrl($item[self::API_DOCUMENT_URL]);

            foreach ($typeClasses as $class => $types) {
                foreach ($types as $name => $value) {
                    $type = (new $class())
                        ->setBrokerage($brokerage)
                        ->setName($name)
                        ->setDescription($value);

                    $manager->persist($type);

                    $this->setReference(sprintf('%s_%s', $class, $name), $type);
                }
            }
            $manager->persist($brokerage);
            $this->setReference($item[self::REFERENCE_ID], $brokerage);
        }

        $manager->flush();
    }

    /**
     * @return int|void
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        return [
            [
                self::REFERENCE_ID => AlpacaConstants::BROKERAGE_NAME,
                self::NAME => AlpacaConstants::BROKERAGE_NAME,
                self::GUID => AlpacaConstants::BROKERAGE_GUID,
                self::CONTEXT => AlpacaConstants::BROKERAGE_CONTEXT,
                self::DESCRIPTION => AlpacaConstants::BROKERAGE_DESCRIPTION,
                self::API_DOCUMENT_URL => AlpacaConstants::BROKERAGE_API_DOCUMENT_URL,
                self::URL => AlpacaConstants::BROKERAGE_URL,
                self::ORDER_CLASSES => AlpacaConstants::ORDER_POSITION_INFO_SERIALIZATION_CONFIG,
                self::ORDER_TYPES => AlpacaConstants::ORDER_TYPES,
                self::POSITION_SIDE_TYPES => AlpacaConstants::POSITION_SIDE_TYPES,
            ],
        ];
    }
}
