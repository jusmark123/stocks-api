<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO;

use App\DTO\Traits\ParametersTrait;
use App\Entity\AbstractGuidEntity;
use App\Entity\Account;
use App\Entity\Job;
use App\Entity\Source;
use App\Message\Job\JobRequestInterface;

/**
 * Class SyncOrdersRequest.
 */
class SyncOrdersRequest extends AbstractGuidEntity implements JobRequestInterface
{
    use ParametersTrait;

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
     * @var array|null
     */
    private $brokerageOrderIds;

    /**
     * @var int
     */
    private $limit;

    /**
     * SyncOrdersRequest constructor.
     *
     * @param Account    $account
     * @param Source     $source
     * @param array      $parameters
     * @param array|null $brokerageOrderIds
     * @param int|null   $limit
     * @param Job|null   $job
     *
     * @throws \Exception
     */
    public function __construct(
        Account $account,
        Source $source,
        array $parameters = [],
        ?array $brokerageOrderIds = [],
        ?int $limit = null,
        ?Job $job = null
    ) {
        parent::__construct();
        $this->job = $job;
        $this->source = $source;
        $this->account = $account;
        $this->parameters = $parameters;
        $this->brokerageOrderIds = $brokerageOrderIds;
        $this->limit = $limit;
    }

    /**
     * @return Job|null
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }

    /**
     * @return array|null
     */
    public function getBrokerageOrderIds(): ?array
    {
        return $this->brokerageOrderIds;
    }

    /**
     * @return int
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }
}
