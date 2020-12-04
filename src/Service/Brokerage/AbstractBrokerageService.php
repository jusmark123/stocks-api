<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\DTO\Brokerage\Interfaces\AccountInfoInterface;
use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Order;
use App\Helper\ValidationHelper;
use App\Service\Brokerage\Interfaces\BrokerageServiceInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

abstract class AbstractBrokerageService implements BrokerageServiceInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var ValidationHelper
     */
    protected $validator;

    /**
     * AbstractBrokerageService constructor.
     *
     * @param LoggerInterface  $logger
     * @param ValidationHelper $validator
     */
    public function __construct(
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->logger = $logger;
        $this->validator = $validator;
    }

    /**
     * @param Account $account
     *
     * @return AccountInfoInterface|null
     */
    abstract public function getAccountInfo(Account $account): ?AccountInfoInterface;

    /**
     * @param OrderInfoInterface $orderInfo
     *
     * @return Order
     */
    abstract public function createOrderFromOrderInfo(OrderInfoInterface $orderInfo): Order;

    /**
     * @param array $orderInfoMessage
     *
     * @return mixed
     */
    abstract public function createOrderInfoFromMessage(array $orderInfoMessage): OrderInfoInterface;

    /**
     * @return string
     */
    abstract public function getConstantsClass(): string;

    /**
     * @param Brokerage $brokerage
     *
     * @return bool
     */
    abstract public function supports(Brokerage $brokerage): bool;

    /**
     * @param Account $account
     * @param string  $uri
     *
     * @return string
     */
    protected function getUri(Account $account, string $uri): string
    {
        return $account->getapiEndpointUrl().$uri;
    }
}
