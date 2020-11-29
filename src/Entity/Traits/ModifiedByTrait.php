<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait ModifiedByTrait
{
    /**
     * @var string
     * @Gedmo\Blameable(on="update")
     * @ORM\Column(name="modified_by", type="string", length=255, nullable=true)
     */
    protected $modifiedBy;

    /**
     * sets modifiedBy.
     *
     * @return $this
     */
    public function setModifiedBy(string $modifiedBy)
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
