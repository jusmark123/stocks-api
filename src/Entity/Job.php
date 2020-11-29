<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\User;
use App\Entity\Source;
use App\Entity\Account;


/**
 * Job.
 *
 * @ORM\Table(
 *		name="job",
 *		uniqueConstraints={
 *			@ORM\UniqueConstraint(name="job_un_guid", columns={"guid"})
 *		},
 *		indexes={},
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
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="jobs", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $account;

    /**
     * @var Source
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="jobs", fetch="LAZY")
     * @ORM\JoinColumns({
     * 		@ORM\JoinColumn(name="source_id", referencedColumnName="id", nullable=false)
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
     * @param string $description
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
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source = source;
    }

    /**
     * @param Source $source
     *
     * @return Job
     */
    public function setSource(Source $source): Job
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return Account $account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return Job
     */
    public function setAccount(Account $account): Job
    {
        $this->account = $account;

        return $this;
    }
}
