<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Entity;

use App\Entity\AbstractEntity;
use App\Helper\ValidationHelper;
use App\Service\AbstractService;
use App\Service\DefaultTypeService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractEntityService.
 */
abstract class AbstractEntityService extends AbstractService
{
    /**
     * @var DefaultTypeService
     */
    protected $defaultTypeService;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ValidationHelper
     */
    protected $validator;

    /**
     * AbstractEntityService constructor.
     *
     * @param DefaultTypeService     $defaultTypeService
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     * @param ValidationHelper       $validator
     */
    public function __construct(
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->defaultTypeService = $defaultTypeService;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        parent::__construct($logger);
    }

    public function checkConnection(): void
    {
        if (!$this->entityManager->isOpen()) {
            $this->entityManager->getConnection()->close();
            $this->entityManager->getConnection()->connect();
        }
    }

    /**
     * @return DefaultTypeService
     */
    public function getDefaultTypeService(): DefaultTypeService
    {
        return $this->defaultTypeService;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @return ValidationHelper
     */
    public function getValidator(): ValidationHelper
    {
        return $this->validator;
    }

    /**
     * @param AbstractEntity $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return AbstractEntity
     */
    public function save(AbstractEntity $entity)
    {
        $this->validate($entity);

        if (null === $entity->getId()) {
            $this->entityManager->persist($entity);
        }
        $this->update();

        return $entity;
    }

    public function update()
    {
        $this->entityManager->flush();
    }

    /**
     * @param AbstractEntity $entity
     */
    public function validate(AbstractEntity $entity)
    {
        $this->validator->validate($entity);
    }
}
