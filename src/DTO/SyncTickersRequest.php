<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO;

use App\DTO\Traits\JobRequestTrait;
use App\DTO\Traits\ParametersTrait;
use App\Entity\Account;
use App\Entity\Job;
use App\Entity\Source;
use App\Entity\Traits\EntityGuidTrait;
use App\Message\Job\JobRequestInterface;

class SyncTickersRequest implements JobRequestInterface
{
    use EntityGuidTrait;
    use JobRequestTrait;
    use ParametersTrait;

    /**
     * @var array
     */
    private $tickers;

    /**
     * @var int|null
     */
    private $limit;

    /**
     * SyncTickersRequest constructor.
     *
     * @param Account    $account
     * @param Source     $source
     * @param array|null $parameters
     * @param int|null   $limit
     * @param Job|null   $job
     *
     * @throws \Exception
     */
    public function __construct(
        Account $account,
        Source $source,
        ?array $parameters = [],
        ?int $limit = null,
        ?Job $job = null
    ) {
        $this->job = $job;
        $this->account = $account;
        $this->source = $source;
        $this->parameters = $parameters;
        $this->limit = $limit;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     *
     * @return SyncTickersRequest
     */
    public function setLimit(?int $limit): SyncTickersRequest
    {
        $this->limit = $limit;

        return $this;
    }
}
