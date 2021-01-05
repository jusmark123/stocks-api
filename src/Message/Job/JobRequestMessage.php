<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job;

/**
 * Class JobRequestMessage.
 */
class JobRequestMessage
{
    /**
     * @var string
     */
    private $accountId;

    /**
     * @var string|null
     */
    private $jobId;

    /**
     * @var JobRequestMessageInterface
     */
    private $requestMessage;

    /**
     * @var string
     */
    private $sourceId;

    /**
     * JobRequestMessage constructor.
     *
     * @param string                     $accountId
     * @param string                     $sourceId
     * @param JobRequestMessageInterface $requestMessage
     */
    public function __construct(
        string $accountId,
        string $sourceId,
        JobRequestMessageInterface $requestMessage
    ) {
        $this->accountId = $accountId;
        $this->requestMessage = $requestMessage;
        $this->sourceId = $sourceId;
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @return string|null
     */
    public function getJobId(): ?string
    {
        return $this->jobId;
    }

    /**
     * @param string|null $jobId
     *
     * @return JobRequestMessage
     */
    public function setJobId(?string $jobId): JobRequestMessage
    {
        $this->jobId = $jobId;

        return $this;
    }

    /**
     * @return JobRequestMessageInterface
     */
    public function getRequestMessage(): JobRequestMessageInterface
    {
        return $this->requestMessage;
    }

    /**
     * @return string
     */
    public function getSourceId(): string
    {
        return $this->sourceId;
    }
}
