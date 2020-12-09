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
 * @ORM\Entity(repositoryClass="App\Entity\Repository\WatchListRepositoru")
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
}
