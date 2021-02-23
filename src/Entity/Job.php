<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Job.
 *
 * @ORM\Table(
 *		name="job",
 *		uniqueConstraints={
 *			@ORM\UniqueConstraint(name="job_un_guid", columns={"guid"})
 *		},
 *		indexes={
 *          @ORM\Index(name="job_ix_name_source_id", columns={"name","source_id"}),
 *          @ORM\Index(name="job_ix_name_account_id", columns={"name","source_id"}),
 *      },
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\JobRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Job extends AbstractGuidEntity
{
    /**
     * @var array|null
     */
    private ?array $config;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private ?string $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="requestHash", type="string", length=255, nullable=true)
     */
    private ?string $requestHash;

    /**
     * @var float
     */
    private float $percentComplete;

    /**
     * @var string|null
     *
     * @ORM\Column(name="error_message", type="text", length=65535, nullable=true)
     */
    private ?string $errorMessage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="error_trace", type="text", length=65535, nullable=true)
     */
    private ?string $errorTrace;

    /**
     * @var int|string
     */
    private $jobItemCount;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private ?string $name;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100, nullable=true)
     */
    private string $status;

    /**
     * @var Account|null
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="jobs", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=true)
     * })
     */
    private ?Account $account;

    /**
     * @var Source|null
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="jobs", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="source_id", referencedColumnName="id", nullable=true)
     * })
     */
    private ?Source $source;

    /**
     * @var ArrayCollection|JobItem[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="JobItem", mappedBy="job", fetch="LAZY", cascade={"persist","remove"})
     */
    private $jobItems;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="received_at", type="datetime", nullable=true)
     */
    private ?\DateTime $receivedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    private ?\DateTime $startedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="processed_at", type="datetime", nullable=true)
     */
    private ?\DateTime $processedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="failed_at", type="datetime", nullable=true)
     */
    private ?\DateTime $failedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="cancelled_at", type="datetime", nullable=true)
     */
    private ?\DateTime $cancelledAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="completed_at", type="datetime", nullable=true)
     */
    private ?\DateTime $completedAt;

    /**
     * Job constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->config = [];
        $this->jobItems = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     *
     * @return Job
     */
    public function setConfig($config = []): Job
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Job
     */
    public function setName(string $name): Job
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string $description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return Job
     */
    public function setDescription(string $description = null): Job
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequestHash(): ?string
    {
        return $this->requestHash;
    }

    /**
     * @param string|null $requestHash
     *
     * @return Job
     */
    public function setRequestHash(?string $requestHash): Job
    {
        $this->requestHash = $requestHash;

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
     * @return Job
     */
    public function setStatus(string $status): Job
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return float
     */
    public function getPercentComplete(): float
    {
        return (float) $this->percentComplete ?? 0.00;
    }

    /**
     * @param float $percentComplete
     *
     * @return Job
     */
    public function setPercentComplete(float $percentComplete = 0.00): Job
    {
        $this->percentComplete = $percentComplete;

        return $this;
    }

    /**
     * @return string|int
     */
    public function getJobItemCount()
    {
        return $this->jobItems->count();
    }

    /**
     * @return string
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     *
     * @return Job
     */
    public function setErrorMessage(?string $errorMessage = null): Job
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorTrace(): ?string
    {
        return $this->errorTrace;
    }

    /**
     * @param string $errorTrace
     *
     * @return Job
     */
    public function setErrorTrace(?string $errorTrace = null): Job
    {
        $this->errorTrace = $errorTrace;

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
     * @return Job
     */
    public function setSource(?Source $source = null): Job
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return Account|null
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * @param Account|null $account
     *
     * @return $this
     */
    public function setAccount(?Account $account = null): Job
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Job
     */
    public function setUser(User $user): Job
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getReceivedAt(): ?\DateTime
    {
        return $this->receivedAt;
    }

    /**
     * @param \DateTime|null $receivedAt
     *
     * @return Job
     */
    public function setReceivedAt(?\DateTime $receivedAt): Job
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStartedAt(): ?\DateTime
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTime|null $startedAt
     *
     * @return Job
     */
    public function setStartedAt(?\DateTime $startedAt): Job
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
     * @return Job
     */
    public function setProcessedAt(?\DateTime $processedAt): Job
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
     * @return Job
     */
    public function setFailedAt(?\DateTime $failedAt): Job
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
     * @return Job
     */
    public function setCancelledAt(?\DateTime $cancelledAt): Job
    {
        $this->cancelledAt = $cancelledAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCompletedAt(): ?\DateTime
    {
        return $this->completedAt;
    }

    /**
     * @param \DateTime|null $completedAt
     *
     * @return Job
     */
    public function setCompletedAt(?\DateTime $completedAt): Job
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    /**
     * @param JobItem $jobItem
     *
     * @return $this
     */
    public function addJobItem(JobItem $jobItem): Job
    {
        $this->jobItems->add($jobItem);

        return $this;
    }

    /**
     * @param JobItem $jobItem
     *
     * @return $this
     */
    public function removeJobItem(JobItem $jobItem): Job
    {
        $this->jobItems->removeElement($jobItem);

        return $this;
    }

    /**
     * @return JobItem[]|ArrayCollection|PersistentCollection
     */
    public function getJobItems()
    {
        return $this->jobItems;
    }

    /**
     * @param string $guid
     *
     * @return JobItem|bool
     */
    public function getJobItem(string $guid)
    {
        return $this->jobItems->filter(function ($jobItem) use ($guid) {
            if ($jobItem->getGuid()->toString() === $guid) {
                return true;
            }

            return false;
        })->first();
    }

    /**
     * @param JobItem[]|ArrayCollection|PersistentCollection $jobItems
     *
     * @return Job
     */
    public function setJobItems($jobItems): Job
    {
        $this->jobItems = $jobItems;

        return $this;
    }
}
