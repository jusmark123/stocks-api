<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job;

use App\Entity\Account;
use App\Entity\Job;
use App\Entity\Source;

/**
 * Class JobNotification.
 */
class JobNotification
{
    /**
     * @var
     */
    private $account;

    /**
     * @var array
     */
    private $config;

    /**
     * @var Job
     */
    protected $job;

    /**
     * @var
     */
    private $source;

    /**
     * JobNotification constructor.
     *
     * @param Account $account
     * @param Source  $source
     * @param array   $config
     */
    public function __construct(Account $account, Source $source, array $config)
    {
        $this->account = $account;
        $this->source = $source;
        $this->config = $config;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }
}
