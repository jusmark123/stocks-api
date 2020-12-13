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
 *          @ORM\Index(name="job_ix_name_user_id", columns={"name","user_id"}),
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
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100, nullable=true)
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="error_message", type="string", length=22, nullable=true)
     */
    private $errorMessage;

    /**
     * @var string
     *
     * @ORM\Column(name="error_trace", type="text", length=65535, nullable=true)
     */
    private $errorTrace;

    /**
     * @var Account|null
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="jobs", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $account;

    /**
     * @var Source|null
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="jobs", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="source_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $source;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="jobs", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $user;

    /**
     * @var ArrayCollection|JobDataItem[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="JobDataItem", mappedBy="job", fetch="LAZY", cascade={"persist","remove"})
     */
    private $jobData;

    /**
     * Job constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->jobData = new ArrayCollection();
        parent::__construct();
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
    public function setErrorMessage(string $errorMessage): Job
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
    public function setErrorTrace(string $errorTrace): Job
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
     * @param Source $source
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
     * @param JobDataItem $jobDataItem
     *
     * @return $this
     */
    public function addJobData(JobDataItem $jobDataItem)
    {
        $this->jobData->add($jobDataItem);

        return $this;
    }

    /**
     * @param JobDataItem $jobDataItem
     *
     * @return $this
     */
    public function removeJobData(JobDataItem $jobDataItem): Job
    {
        $this->jobData->removeElement($jobDataItem);

        return $this;
    }

    /**
     * @return JobDataItem[]|ArrayCollection|PersistentCollection
     */
    public function getJobData()
    {
        return $this->jobData;
    }

    /**
     * @param JobDataItem[]|ArrayCollection|PersistentCollection $jobDataItems
     *
     * @return Job
     */
    public function setJobData($jobDataItems): Job
    {
        $this->jobData = $jobDataItems;

        return $this;
    }
}
