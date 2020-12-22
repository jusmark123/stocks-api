<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class TickerType.
 *
 * @ORM\Table(
 *     name="ticker_type",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="ticker_type_un_guid", columns={"guid"}),
 *          @ORM\UniqueConstraint(name="ticker_type_un_name", columns={"name"}),
 *     },
 *     indexes={
 *          @ORM\Index(name="ticker_type_un_code", columns={"code"}),
 *     }
 * )
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class TickerType extends AbstractGuidEntity
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
     * @ORM\Column(name="code", type="string", length=20, nullable=false)
     */
    private $code;

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
     * @return TickerType
     */
    public function setName(string $name): TickerType
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return TickerType
     */
    public function setCode(string $code): TickerType
    {
        $this->code = $code;

        return $this;
    }
}
