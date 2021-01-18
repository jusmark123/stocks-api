<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO;

use App\DTO\Traits\JobRequestTrait;
use App\DTO\Traits\ParametersTrait;
use App\Entity\AbstractGuidEntity;
use App\Entity\Account;
use App\Entity\Job;
use App\Entity\Source;
use App\Entity\Traits\EntityGuidTrait;
use App\Message\Job\JobRequestInterface;

class SyncPositionsRequest extends AbstractGuidEntity implements JobRequestInterface
{
    use EntityGuidTrait;
    use JobRequestTrait;
    use ParametersTrait;

    /**
     * @var int|null
     */
    private $limit;

    /**
     * SyncPositionsRequest constructor.
     *
     * @param Account  $account
     * @param Source   $source
     * @param array    $parameters
     * @param int|null $limit
     * @param Job|null $job
     *
     * @throws \Exception
     */
    public function __construct(
        Account $account,
        Source $source,
        array $parameters = [],
        ?int $limit = null,
        ?Job $job = null
    ) {
        parent::__construct();
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
}
