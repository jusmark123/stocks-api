<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TickerRepository.
 */
class TickerRepository extends EntityRepository
{
    /**
     * @param string $symbol
     * @param array  $brokerages
     *
     * @return int|mixed|string
     */
    public function findBrokerageTicker(string $symbol, array $brokerages)
    {
        return $this->createQueryBuilder('t')
            ->where(':brokerage_ids MEMBER OF t.brokerages')
            ->setParameter('brokerage_ids', $brokerages)
            ->getQuery()
            ->getResult();
    }
}
