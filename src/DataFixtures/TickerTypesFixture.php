<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Brokerage\AlpacaConstants;
use App\Entity\Brokerage;
use App\Entity\TickerType;
use App\Service\Brokerage\BrokerageServiceProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class TickerTypesFixture extends AbstractDataFixture
{
    /**
     * @var BrokerageServiceProvider
     */
    private $brokerageServiceProvider;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * TickerTypesFixture constructor.
     *
     * @param BrokerageServiceProvider $brokerageServiceProvider
     * @param EntityManagerInterface   $entityManager
     */
    public function __construct(
        BrokerageServiceProvider $brokerageServiceProvider,
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->brokerageServiceProvider = $brokerageServiceProvider;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var Brokerage $brokerage */
        $brokerage = $this->getReference(AlpacaConstants::BROKERAGE_NAME);
        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($brokerage);
        $brokerageService->syncTickerTypes();

        foreach ($this->entityManager->getRepository(TickerType::class)->findAll() as $item) {
            $this->setReference('ticker_type_'.$item->getCode(), $item);
        }
    }

    public function getOrder()
    {
        return 4;
    }

    protected function getData(): array
    {
        // TODO: Implement getData() method.
    }
}
