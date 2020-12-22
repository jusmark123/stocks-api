<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message;

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
    private $job;

    /**
     * @var
     */
    private $config;

    /**
     * @var
     */
    private $source;

    /**
     * @var
     */
    private $account;

    /**
     * JobNotification constructor.
     *
     * @param Job   $job
     * @param array $config
     */
    public function __construct(
        Job $job,
        array $config = []
    ) {
        $this->job = $job;
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
