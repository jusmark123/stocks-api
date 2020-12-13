<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Entity;

use App\Entity\Brokerage;
use App\Entity\Ticker;
use App\Helper\ValidationHelper;
use App\Service\DefaultTypeService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class TickerEntityService extends AbstractEntityService
{
    private $tickerTypeService;

    public function __construct(
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        TickerTypeEntityService $tickerTypeService,
        ValidationHelper $validator
    ) {
        $this->tickerTypeService = $tickerTypeService;
        parent::__construct($defaultTypeService, $entityManager, $logger, $validator);
    }

    /**
     * @return TickerTypeEntityService
     */
    public function getTickerTypeService(): TickerTypeEntityService
    {
        return $this->tickerTypeService;
    }

    /**
     * @return mixed
     */
    public function getTickerTypes()
    {
        return $this->tickerTypeService->getTickerTypes();
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return Ticker|object|null
     */
    public function getTickers(Brokerage $brokerage)
    {
        return $this->entityManager
            ->getRepository(Ticker::class)
            ->findOneBy(['brokerages' => [$brokerage]]);
    }

    /**
     * @param string $symbol
     *
     * @return Ticker|object|null
     */
    public function getTicker(string $symbol)
    {
        return $this->entityManager
            ->getRepository(Ticker::class)
            ->findOneBy(['symbol' => $symbol]);
    }
}

