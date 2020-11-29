<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Client\BrokerageClient;
use App\Entity\Account;
use App\Entity\Brokerage;
use App\Entity\Interfaces\AccountInfoInterface;
use App\Entity\Manager\AccountEntityManager;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param AccountEntityManager $entityManager
     * @param BrokerageClient      $brokerageClient
     * @param LoggerInterface      $logger
     */
    public function __construct(
      AccountEntityManager $accountEntityManager,
      BrokerageClient $brokerageClient,
      iterable $brokerageServices,
      LoggerInterface $logger)
    {
        $this->accountEntityManager = $accountEntityManager;
        $this->brokerageClient = $brokerageClient;
        $this->brokerageServices = $brokerageServices;
        $this->logger = $logger;
    }

    /**
     * @param Account $account
     *
     * @return AccountService
     */
    public function getAccountInfo(Account $account): AccountInfoInterface
    {
        $this->setBrokerageService($account->getBrokerage());

        return $this->brokerageService->getAccountInfo($account);
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
        foreach ($this->brokerageServices as $brokerageService) {
            if ($brokerageService->supports($brokerage)) {
                break;
            }

            if (null === $this->brokerageService) {
                throw new NotFoundHttpException(BROKERAGE_SERVICE_NOT_FOUND);
            }
        }

        $this->brokerageService = $brokerageService;
    }
}
