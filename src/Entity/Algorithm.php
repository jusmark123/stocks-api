<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

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
 *     indexes={}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\AlgorithmRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Algorithm extends AbstractGuidEntity
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
     * @ORM\Column(name="filename", type="string", length=100, nullable=false)
     */
    private $filename;

    /**
     * @var mixed
     *
     * @ORM\Column(name="config", type="text", length=65535, nullable=true)
     */
    private $config;

    /**
     * @var Source
     * @ORM\ManyToOne(targetEntity="Source", fetch="LAZY")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="source_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $source;

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
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     *
     * @return Algorithm
     */
    public function setFilename(string $filename): Algorithm
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     *
     * @return Algorithm
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return Source
     */
    public function getSource(): Source
    {
        return $this->source;
    }

    /**
     * @param Source $source
     *
     * @return Algorithm
     */
    public function setSource(Source $source): Algorithm
    {
        $this->source = $source;

        return $this;
    }
}
