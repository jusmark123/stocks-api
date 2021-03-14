<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Brokerage\AlpacaConstants;
use App\Entity\Brokerage;
use App\Entity\Factory\OrderStatusTypeFactory;
use App\Service\Brokerage\BrokerageServiceProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class OrderStatusTypeFixture extends AbstractDataFixture
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var BrokerageServiceProvider
     */
    private BrokerageServiceProvider $brokerageServiceProvider;

    /**
     * OrderStatusTypeFixture constructor.
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

        foreach (AlpacaConstants::ORDER_STATUSES as $key => $orderStatus) {
            $item = OrderStatusTypeFactory::create()
                ->setBrokerage($brokerage)
                ->setName($key)
                ->setDescription($orderStatus);
            $this->entityManager->persist($item);
            $this->setReference(sprintf('order_status_type_%s', $key), $item);
        }

        $this->entityManager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        return [];
    }
}
