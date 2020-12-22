<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Account;
use App\Entity\Source;

/**
 * Class SyncOrdersRequest.
 */
class SyncOrdersRequest
{
    /**
     * @var Account
     */
    private $account;

    /**
     * @var array|null
     */
    private $brokerageOrderIds;

    /**
     * @var array|null
     */
    private $filters;

    /**
     * @var Source|null
     */
    private $source;

    /**
     * @var int
     */
    private $limit;

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return SyncOrdersRequest
     */
    public function setAccount(Account $account): SyncOrdersRequest
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getBrokerageOrderIds(): ?array
    {
        return $this->brokerageOrderIds;
    }

    /**
     * @param array|null $brokerageOrderIds
     *
     * @return SyncOrdersRequest
     */
    public function setBrokerageOrderIds(?array $brokerageOrderIds = []): SyncOrdersRequest
    {
        $this->brokerageOrderIds = $brokerageOrderIds;

        return $this;
    }

    /**
     * @return Source|null
     */
    public function getSource(): ?Source
    {
        return $this->source;
    }

    /**
     * @return array|null
     */
    public function getFilters(): ?array
    {
        return $this->filters;
    }

    /**
     * @param array|null $filters
     *
     * @return SyncOrdersRequest
     */
    public function setFilters(?array $filters): SyncOrdersRequest
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @return ?int
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param ?int $limit
     *
     * @return SyncOrdersRequest
     */
    public function setLimit(?int $limit = 50): SyncOrdersRequest
    {
        $this->limit = $limit;

        return $this;
    }
}
