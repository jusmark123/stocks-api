<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Entity\Account;
use App\Service\DefaultTypeService;
use App\Service\OrderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AlpacaBrokerageService extends AbstractBrokerageService
{
    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * @var DefaultTypeService
     */
    private $defaultTypeService;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    public function __construct(
        AccountService $accountService,
        DefaultTypeService $defaultTypeService,
        OrderService $orderService
    ) {
        $this->accountService = $accountService;
        $this->defaultTypeService = $defaultTypeService;
        $this->orderService = $orderService;
    }

    public function getAccountInfo(Account $account): AccountInfoInterface
    {
        $url = $account->getApiUrl();
    }

    public function getOrders(Account $account): BrokerageOrderInterface
    {
    }
}
