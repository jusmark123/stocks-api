<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\DeactivatedAtTrait;
use App\Entity\Traits\EntityGuidTrait;
use App\Entity\Traits\ModifiedAtTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Algorithm.
 *
 * @ORM\Table(
 *      name="algorithm",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="algorithm_un_guid", columns={"guid"}),
 *      },
 *      indexes={}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\AlgorithmRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Algorithm extends Source
{
    use CreatedAtTrait;
    use DeactivatedAtTrait;
    use EntityGuidTrait;
    use ModifiedAtTrait;
    use Traits\EntityGuidTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private string $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private string $description;

    /**
     * @var mixed
     *
     * @ORM\Column(name="config", type="text", length=65535, nullable=true)
     */
    private $config;

    /**
     * Algorithm constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
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
     * @return Algorithm
     */
    public function setName(string $name): Algorithm
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
     * @return Algorithm
     */
    public function setDescription(string $description): Algorithm
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return json_decode($this->config, true);
    }

    /**
     * @param mixed $config
     *
     * @return Algorithm
     */
    public function setConfig(array $config): Algorithm
    {
        $this->config = json_encode($config);

        return $this;
    }
}
