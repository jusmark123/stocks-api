<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Traits;

use App\Entity\Account;
use App\Entity\Job;
use App\Entity\Source;

/**
 * Trait JobRequestTrait.
 */
trait JobRequestTrait
{
    /**
     * @var Job|null
     */
    private $job;

    /**
     * @var Account
     */
    private $account;

    /**
     * @var Source
     */
    private $source;

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
     * @return $this
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Job|null
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @param Job|null $job
     *
     * @return $this
     */
    public function setJob(?Job $job)
    {
        $this->job = $job;

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
     * @param Source|null $source
     *
     * @return $this
     */
    public function setSource(?Source $source)
    {
        $this->source = $source;

        return $this;
    }
}
