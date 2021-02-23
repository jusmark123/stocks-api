<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Brokerage\AlpacaConstants;
use App\Entity\Factory\BrokerageFactory;
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
    const PAPER_ACCOUNTS = 'paper_accounts';
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
            $brokerage = BrokerageFactory::create()
                ->setGuid(Uuid::fromString($item[self::GUID]))
                ->setName($item[self::NAME])
                ->setContext($item[self::CONTEXT])
                ->setDescription($item[self::DESCRIPTION])
                ->setUrl($item[self::URL])
                ->setApiDocumentUrl($item[self::API_DOCUMENT_URL])
                ->setPaperAccounts($item[self::PAPER_ACCOUNTS]);

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
                self::PAPER_ACCOUNTS => true,
            ],
        ];
    }
}
