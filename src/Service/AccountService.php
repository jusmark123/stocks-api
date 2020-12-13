<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Client\BrokerageClient;
use App\DTO\Brokerage\Interfaces\AccountInfoInterface;
use App\Entity\Account;
use App\Service\Brokerage\BrokerageServiceProvider;
use App\Service\Entity\AccountEntityService;
use Psr\Log\LoggerInterface;

/**
 * Class AccountService.
 */
class AccountService extends AbstractService
{
    /**
     * @var AccountEntityService
     */
    private $accountEntityService;

    /**
     * @var BrokerageClient
     */
    private $brokerageClient;

    /**
     * @var BrokerageServiceProvider
     */
    private $brokerageServiceProvider;

    /**
     * AccountService constructor.
     *
     * @param AccountEntityService     $accountEntityService
     * @param BrokerageClient          $brokerageClient
     * @param BrokerageServiceProvider $brokerageServiceProvider
     * @param LoggerInterface          $logger
     */
    public function __construct(
        AccountEntityService $accountEntityService,
        BrokerageClient $brokerageClient,
        BrokerageServiceProvider $brokerageServiceProvider,
        LoggerInterface $logger
    ) {
        $this->accountEntityService = $accountEntityService;
        $this->brokerageClient = $brokerageClient;
        $this->brokerageServiceProvider = $brokerageServiceProvider;
        parent::__construct($logger);
    }

    /**
     * @param Account $account
     *
     * @return AccountInfoInterface
     */
    public function getAccountInfo(Account $account): AccountInfoInterface
    {
        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());

        return $brokerageService->getAccountInfo($account);
    }
}
