<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage;

use App\DTO\Traits\ParametersTrait;
use App\Entity\Account;

/**
 * Class AccountHistoryRequest.
 */
class AccountHistoryRequest implements AccountHistoryRequestInterface
{
    use ParametersTrait;

    /**
     * @var Account
     */
    private Account $account;

    /**
     * AccountHistoryRequest constructor.
     *
     * @param Account $account
     * @param array   $parameters
     */
    public function __construct(Account $account, array $parameters)
    {
        $this->account = $account;
        $this->parameters = $parameters;
    }

    /**
     * @return mixed
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     *
     * @return AccountHistoryRequest
     */
    public function setAccount($account): AccountHistoryRequest
    {
        $this->account = $account;

        return $this;
    }
}
