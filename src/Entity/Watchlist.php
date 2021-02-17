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
 * Class Watchlist.
 *
 * @ORM\Table(
 *      name="watchlist",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="watch_list_un_guid", columns={"guid"}),
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\WatchListRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Watchlist extends AbstractGuidEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
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
     *
     * @ORM\Column(name="type", type="string", length=50, nullable=false)
     */
    private $type;

    /**
     * @var Account
     */
    private $account;

    /**
     * @var ArrayCollection|Ticker[]|PersistentCollection
     */
    private $tickers;

    public function __construct()
    {
        parent::__construct();
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
     * @return Watchlist
     */
    public function setName(string $name): Watchlist
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
     * @return Watchlist
     */
    public function setDescription(string $description): Watchlist
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Watchlist
     */
    public function setType(string $type): Watchlist
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     *
     * @return Watchlist
     */
    public function setAccount(Account $account): Watchlist
    {
        $this->account = $account;

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
     * @return Watchlist
     */
    public function setTickers($tickers)
    {
        $this->tickers = $tickers;

        return $this;
    }
}
