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
 * Class TickerSector.
 *
 * @ORM\Table(
 *     uniqueConstraints={
 * 		  @ORM\UniqueConstraint(name="source_type_un_name", columns={"name"})
 *     }
 * )
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class TickerSector extends AbstractDefaultEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private string $name;

    /**
     * @var ArrayCollection|Ticker[]|PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Ticker", mappedBy="sector", fetch="LAZY")
     */
    private $tickers;

    public function __construct()
    {
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
     * @return TickerSector
     */
    public function setName(string $name): TickerSector
    {
        $this->name = $name;

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
     * @return TickerSector
     */
    public function setTickers($tickers)
    {
        $this->tickers = $tickers;

        return $this;
    }
}
