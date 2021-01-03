<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Entity\Brokerage;
use App\Exception\BrokerageServiceConfigurationException;

/**
 * Class BrokerageServiceProvider.
 */
class BrokerageServiceProvider implements BrokerageServiceProviderInterface
{
    /**
     * @var iterable
     */
    private $brokerageServices;

    /**
     * BrokerageServiceProvider constructor.
     *
     * @param array $brokerageServices
     *
     * @throws BrokerageServiceConfigurationException
     */
    public function __construct(
        iterable $brokerageServices
    ) {
        if ($brokerageServices instanceof \Traversable) {
            $brokerageServices = iterator_to_array($brokerageServices);
        }

        $this->setBrokerageServices($brokerageServices);
    }

    /**
     * @param Brokerage $brokerage
     *
     * @return BrokerageServiceInterface
     */
    public function getBrokerageService(Brokerage $brokerage): BrokerageServiceInterface
    {
        foreach ($this->brokerageServices as $brokerageService) {
            if ($brokerageService->supports($brokerage)) {
                return $brokerageService;
            }
        }
    }

    /**
     * @return iterable
     */
    public function getBrokerageServices(): iterable
    {
        return $this->brokerageServices;
    }

    /**
     * @param array $brokerageServices
     *
     * @throws BrokerageServiceConfigurationException
     *
     * @return BrokerageServiceProviderInterface
     */
    public function setBrokerageServices(array $brokerageServices): BrokerageServiceProviderInterface
    {
        $this->brokerageServices = [];

        foreach ($brokerageServices as $brokerageService) {
            if (!$brokerageService instanceof BrokerageServiceInterface) {
                throw new BrokerageServiceConfigurationException(
                    'jobHandlers must implement('.BrokerageServiceInterface::class.')');
            }
        }

        $this->brokerageServices = $brokerageServices;

        return $this;
    }
}
