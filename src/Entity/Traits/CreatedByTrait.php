<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait CreatedByTrait
{
    /**
     * @var string|null
     * @Gedmo\Blameable(on="create")
     * @ORM\Column(name="created_by", type="string", length=255, nullable=true)
     */
    protected $createdBy;

    /**
     * Sets createdBy.
     *
     * @return $this
     */
    public function setCreatedBy(string $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Returns createdBy.
     *
     * @return $this
     */
    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }
}
