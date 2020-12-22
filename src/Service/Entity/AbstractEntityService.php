<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Entity;

use App\Entity\Factory\AbstractFactory;
use App\Entity\Manager\AbstractEntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

abstract class AbstractEntityService extends AbstractService
{
    /** @var AbstractEntityManager */
    protected $entityManager;

    /** @var AbstractFactory|null */
    protected $factory;

    /**
     * AbstractEntityService Constructor.
     *
     * @param AbstractEntityManager $entityManager
     * @param AbstractFactory       $factory
     */
    public function __construct(
         AbstractEntityManager $entityManager,
         AbstractFactory $factory
        ) {
        $this->entityManager = $entityManager;
        $this->factory = $factory;
    }

    /**
     * @return AbstractEntityManager
     */
    public function getEntityManager(): AbstractEntityManager
    {
        return $this->entityManager;
    }

    /**
     * @return AbstractFactory
     */
    public function getFactory(): AbstractFactory
    {
        return $this->factory;
    }

    /**
     * @return ClassMetadata
     */
    public function getMetadataFor(): ClassMetadata
    {
        return $this->entityManager->getMetadataFor();
    }
}
