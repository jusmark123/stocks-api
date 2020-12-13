<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Entity\Brokerage;

/**
 * Interface BrokerageServiceProviderInterface.
 */
interface BrokerageServiceProviderInterface
{
    /**
     * @param Brokerage $brokerage
     *
     * @return BrokerageServiceInterface
     */
    public function getBrokerageService(Brokerage $brokerage): BrokerageServiceInterface;

    /**
     * @param array $brokerageServices
     *
     * @return BrokerageServiceProviderInterface
     */
    public function setBrokerageServices(array $brokerageServices): BrokerageServiceProviderInterface;

    /**
     * @return array
     */
    public function getBrokerageServices(): array;
}
