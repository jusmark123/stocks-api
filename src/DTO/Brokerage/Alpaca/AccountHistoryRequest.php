<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Alpaca;

use App\DTO\Brokerage\BrokerageAccountHistoryRequestInterface;
use App\Entity\Account;

class AccountHistoryRequest implements BrokerageAccountHistoryRequestInterface
{
    private $account;

    private $filters;

    private $limit = 50;

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

    /**
     * @return mixed
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param mixed $filters
     *
     * @return AccountHistoryRequest
     */
    public function setFilters($filters): AccountHistoryRequest
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return AccountHistoryRequest
     */
    public function setLimit(int $limit): AccountHistoryRequest
    {
        $this->limit = $limit;

        return $this;
    }
}
