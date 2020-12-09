<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Screener.
 *
 * @ORM\Table(
 *      name="screener",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="screener_un_guid", columns={"guid"}),
 *      },
 *     indexes={}
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\ScreenerRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deactivatedAt", timeAware=false)
 */
class Screener extends AbstractGuidEntity
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
     * @return Screener
     */
    public function setName(string $name): Screener
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
     * @return Screener
     */
    public function setDescription(string $description): Screener
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
     * @return Screener
     */
    public function setFilename(string $filename): Screener
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
     * @return Screener
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
     * @return Screener
     */
    public function setSource(Source $source): Screener
    {
        $this->source = $source;

        return $this;
    }
}
