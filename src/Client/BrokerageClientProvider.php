<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Client;

use App\Entity\Brokerage;
use App\Exception\BrokerageClientConfigurationException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

class BrokerageClientProvider implements BrokerageClientProviderInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var BrokerageClientInterface[]
     */
    private array $brokerageClients;

    /**
     * BrokerageClientProvider constructor.
     *
     * @param array           $brokerageClients
     * @param LoggerInterface $logger
     */
    public function __construct(
        array $brokerageClients,
        LoggerInterface $logger
    ) {
        $this->setLogger($logger);
        if ($brokerageClients instanceof \Traversable) {
            $brokerageClients = iterator_to_array($brokerageClients);
        }
        $this->setBrokerageClients($brokerageClients);
    }

    /**
     * @param array $brokerageClients
     *
     * @return BrokerageClientProviderInterface
     */
    public function setBrokerageClients(array $brokerageClients): BrokerageClientProviderInterface
    {
        $this->brokerageClients = $brokerageClients;

        return $this;
    }

    /**
     * @return array
     */
    public function getBrokerageClients(): array
    {
        return $this->brokerageClients;
    }

    /**
     * @param Brokerage $brokerage
     *
     * @throws BrokerageClientConfigurationException
     *
     * @return BrokerageClientInterface|null
     */
    public function getBrokerageClient(Brokerage $brokerage): ?BrokerageClientInterface
    {
        foreach ($this->brokerageClients as $brokerageClient) {
            if (!$brokerageClient instanceof BrokerageClientInterface) {
                throw new BrokerageClientConfigurationException('brokerage client must implement('.BrokerageClientInterface::class.')');
            }

            if ($brokerageClient->supports($brokerage)) {
                return $brokerageClient;
            }
        }

        return null;
    }
}
