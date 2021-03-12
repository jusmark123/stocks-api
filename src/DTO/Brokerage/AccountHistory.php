<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage;

use App\Entity\AbstractGuidEntity;

class AccountHistory extends AbstractGuidEntity
{
    /**
     * @var AccountHistoryInterface[]
     */
    private array $activities = [];

    /**
     * @var AccountHistoryRequestInterface
     */
    private AccountHistoryRequestInterface $request;

    /**
     * @return AccountHistoryInterface[]
     */
    public function getActivities(): array
    {
        return $this->activities;
    }

    /**
     * @param AccountHistoryInterface[] $activities
     *
     * @return AccountHistory
     */
    public function setActivities(array $activities): AccountHistory
    {
        $this->activities = $activities;

        return $this;
    }

    /**
     * @return AccountHistoryRequestInterface
     */
    public function getRequest(): AccountHistoryRequestInterface
    {
        return $this->request;
    }

    /**
     * @param AccountHistoryRequestInterface $request
     *
     * @return AccountHistory
     */
    public function setRequest(AccountHistoryRequestInterface $request): AccountHistory
    {
        $this->request = $request;

        return $this;
    }
}
