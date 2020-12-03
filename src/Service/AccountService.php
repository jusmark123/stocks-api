<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Client\BrokerageClient;
use App\DTO\Brokerage\Interfaces\AccountInfoInterface;
use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Manager\AccountEntityManager;
use App\Helper\ValidationHelper;
use App\Service\Brokerage\Interfaces\BrokerageServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccountService extends AbstractService
{
    const BROKERAGE_SERVICE_NOT_FOUND = 'Brokerage Service not found';

    /**
     * @var AccountEntityManager
     */
    private $accountEntityManager;

    /**
     * @var BrokerageServiceInterface
     */
    private $brokerageService;

    /**
     * @var iterable
     */
    private $brokerageServices;

    /**
     * AccountService constructor.
     *
     * @param AccountEntityManager $accountEntityManager
     * @param BrokerageClient      $brokerageClient
     * @param iterable             $brokerageServices
     * @param LoggerInterface      $logger
     * @param ValidationHelper     $validator
     */
    public function __construct(
        AccountEntityManager $accountEntityManager,
        BrokerageClient $brokerageClient,
        iterable $brokerageServices,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->accountEntityManager = $accountEntityManager;
        $this->brokerageClient = $brokerageClient;
        $this->brokerageServices = $brokerageServices;
        parent::__construct($validator, $logger);
    }

    /**
     * @param Account $account
     *
     * @return AccountInfoInterface
     */
    public function getAccountInfo(Account $account): AccountInfoInterface
    {
        $this->setBrokerageService($account->getBrokerage());

        return $this->brokerageService->getAccountInfo($account);
    }

    /**
     * @param Account    $account
     * @param array|null $filters
     *
     * @return array
     */
    public function getOrderHistory(Account $account, array $filters = []): array
    {
        $this->setBrokerageService($account->getBrokerage());

        return $this->brokerageService->getOrderHistory($account, $filters);
    }

    /**
     * @param Account $account
     * @param array   $orderInfoMessage
     *
     * @return OrderInfoInterface|null
     */
    public function createOrderInfoFromMessage(Account $account, array $orderInfoMessage): ?OrderInfoInterface
    {
        $this->setBrokerageService($account->getBrokerage());

        return $this->brokerageService->getOrderInfoFromArray($orderInfoMessage);
    }

    /**
     * @return BrokerageServiceInterface
     */
    public function getBrokerageService(): BrokerageServiceInterface
    {
        return $this->brokerageService;
    }

    /**
     * @param Brokerage $brokerage
     */
    public function setBrokerageService(Brokerage $brokerage): void
    {
        $brokerageService = null;

        foreach ($this->brokerageServices as $brokerageService) {
            if ($brokerageService->supports($brokerage)) {
                break;
            }

            if (null === $this->brokerageService) {
                throw new NotFoundHttpException(self::BROKERAGE_SERVICE_NOT_FOUND);
            }
        }

        $this->brokerageService = $brokerageService;
    }
}
