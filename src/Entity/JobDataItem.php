<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * JobDataItem.
 *
 * @ORM\Table(
 *		name="job_data_item",
 *		uniqueConstraints={
 *			@ORM\UniqueConstraint(name="job_data_item_un_guid", columns={"guid"})
 *		},
 * )
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class JobDataItem extends AbstractGuidEntity
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
     * @var Job
     *
     * @ORM\ManyToOne(targetEntity="Job", inversedBy="jobData", fetch="LAZY")
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
     * @return JobDataItem
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
     * @return JobDataItem
     */
    public function setStatus(string $status): JobDataItem
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
     * @return JobDataItem
     */
    public function setJob(Job $job): JobDataItem
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
    public function setErrorMessage(string $errorMessage): JobDataItem
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
    public function setErrorTrace(?string $errorTrace = null): JobDataItem
    {
        $this->errorTrace = $errorTrace;

        return $this;
    }
}
