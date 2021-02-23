<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Traits EntityGuidTrait.
 */
trait EntityGuidTrait
{
    /**
     * @var UuidInterface
     *
     * @ORM\Column(name="guid", type="uuid", nullable=false)
     */
    protected UuidInterface $guid;

    /**
     * GuidAwareTrait constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setGuid(Uuid::uuid4());
    }

    /**
     * @return Uuid
     */
    public function getGuid(): UuidInterface
    {
        return $this->guid;
    }

    /**
     * @param UuidInterface $guid
     *
     * @return self
     */
    public function setGuid(UuidInterface $guid): self
    {
        $this->guid = $guid;

        return $this;
    }
}
