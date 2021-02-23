<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait ModifiedAtTrait
{
    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="modified_at", type="datetime", nullable=false)
     */
    protected DateTime $modifiedAt;

    /**
     * @var string
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="modified_by", type="string", length=255, nullable=true)
     */
    protected $modifiedBy;

    /**
     * Sets modifiedAt.
     *
     * @param DateTime $modifiedAt
     *
     * @return $this
     */
    public function setModifiedAt(DateTime $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Return modifiedAt.
     *
     * @return DateTime modifiedAt
     */
    public function getModifiedAt(): DateTime
    {
        return $this->modifiedAt;
    }

    /**
     * sets modifiedBy.
     *
     * @param string $modifiedBy
     *
     * @return $this
     */
    public function setModifiedBy(string $modifiedBy): self
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    /**
     * Returns modifiedBy.
     *
     * @return $this
     */
    public function getModifiedBy(): ?string
    {
        return $this->modifiedBy;
    }
}
