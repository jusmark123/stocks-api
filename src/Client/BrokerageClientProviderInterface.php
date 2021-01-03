<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Client;

use App\Entity\Brokerage;

/**
 * Interface BrokerageClientProviderInterface.
 */
interface BrokerageClientProviderInterface
{
    /**
     * @return array
     */
    public function getBrokerageClients(): array;

    /**
     * @param array $brokerageClients
     *
     * @return BrokerageClientProviderInterface
     */
    public function setBrokerageClients(array $brokerageClients): BrokerageClientProviderInterface;

    /**
     * @param Brokerage $brokerage
     *
     * @return BrokerageClientInterface
     */
    public function getBrokerClient(Brokerage $brokerage): BrokerageClientInterface;
}
