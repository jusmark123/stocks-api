<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * JobItem.
 *
 * @ORM\Table(
 *		name="job_item",
 *		uniqueConstraints={
 *			@ORM\UniqueConstraint(name="job_item_un_guid", columns={"guid"})
 *		},
 * )
 * @ORM\Entity()
 * @ORM\EntityListeners({"App\Entity\Listener\JobItemEntityListener"})
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class JobItem extends AbstractGuidEntity
{
    /**
     * @var mixed|null
     *
     * @ORM\Column(name="data", type="text", length=65535, nullable=true)
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=false)
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="error_message", type="string", length=22, nullable=true)
     */
    private $errorMessage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="error_trace", type="text", length=65535, nullable=true)
     */
    private $errorTrace;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="received_at", type="datetime", nullable=true)
     */
    private $receivedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    private $startedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="processed_at", type="datetime", nullable=true)
     */
    private $processedAt = null;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="failed_at", type="datetime", nullable=true)
     */
    private $failedAt = null;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="cancelled_at", type="datetime", nullable=true)
     */
    private $cancelledAt = null;

    /**
     * @var Job
     *
     * @ORM\ManyToOne(targetEntity="Job", inversedBy="jobItems", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="job_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $job;

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return unserialize($this->data);
    }

    /**
     * @param mixed|null $data
     *
     * @return JobItem
     */
    public function setData($data)
    {
        $this->data = serialize($data);

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return JobItem
     */
    public function setStatus(string $status): JobItem
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * @param Job $job
     *
     * @return JobItem
     */
    public function setJob(Job $job): JobItem
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @param string|null $errorMessage
     *
     * @return $this
     */
    public function setErrorMessage(string $errorMessage): JobItem
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getErrorTrace(): ?string
    {
        return $this->errorTrace;
    }

    /**
     * @param string|null $errorTrace
     *
     * @return $this
     */
    public function setErrorTrace(?string $errorTrace = null): JobItem
    {
        $this->errorTrace = $errorTrace;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReceivedAt(): \DateTime
    {
        return $this->receivedAt;
    }

    /**
     * @param \DateTime $receivedAt
     *
     * @return JobItem
     */
    public function setReceivedAt(\DateTime $receivedAt): JobItem
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTime $startedAt
     *
     * @return JobItem
     */
    public function setStartedAt(\DateTime $startedAt): JobItem
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getProcessedAt(): ?\DateTime
    {
        return $this->processedAt;
    }

    /**
     * @param \DateTime|null $processedAt
     *
     * @return JobItem
     */
    public function setProcessedAt(?\DateTime $processedAt): JobItem
    {
        $this->processedAt = $processedAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getFailedAt(): ?\DateTime
    {
        return $this->failedAt;
    }

    /**
     * @param \DateTime|null $failedAt
     *
     * @return JobItem
     */
    public function setFailedAt(?\DateTime $failedAt): JobItem
    {
        $this->failedAt = $failedAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCancelledAt(): ?\DateTime
    {
        return $this->cancelledAt;
    }

    /**
     * @param \DateTime|null $cancelledAt
     *
     * @return JobItem
     */
    public function setCancelledAt(?\DateTime $cancelledAt): JobItem
    {
        $this->cancelledAt = $cancelledAt;

        return $this;
    }
}
