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
 * Class Stream.
 *
 * @ORM\Table(
 *      name="stream",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="stream_un_guid", columns="guid")
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\StreamRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Stream extends AbstractGuidEntity
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     */
    private $status;

    /**
     * @var ArrayCollection|Account[]|PersistentCollection
     */
    private $accounts;

    /**
     * @var ArrayCollection|Source[]|PersistentCollection
     */
    private $sources;

    /**
     * @var ArrayCollection|Ticker[]|PersistentCollection
     */
    private $tickers;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->sources = new ArrayCollection();
        $this->tickers = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Stream
     */
    public function setName(string $name): Stream
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Stream
     */
    public function setDescription(string $description): Stream
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
     * @return Stream
     */
    public function setStatus(string $status): Stream
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Account[]|ArrayCollection|PersistentCollection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @param Account[]|ArrayCollection|PersistentCollection $accounts
     *
     * @return Stream
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;

        return $this;
    }

    /**
     * @return Source[]|ArrayCollection|PersistentCollection
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * @param Source[]|ArrayCollection|PersistentCollection $sources
     *
     * @return Stream
     */
    public function setSources($sources)
    {
        $this->sources = $sources;

        return $this;
    }

    /**
     * @return Ticker[]|ArrayCollection|PersistentCollection
     */
    public function getTickers()
    {
        return $this->tickers;
    }

    /**
     * @param Ticker[]|ArrayCollection|PersistentCollection $tickers
     *
     * @return Stream
     */
    public function setTickers($tickers)
    {
        $this->tickers = $tickers;

        return $this;
    }
}
