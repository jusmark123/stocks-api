<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Entity\Interfaces\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

/**
 * Class EntityStateService.
 */
class EntityStateService
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * EntityStateService constructor.
     *
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return bool
     */
    public function isManaged(EntityInterface $entity): bool
    {
        return $this->is($entity, UnitOfWork::STATE_MANAGED);
    }

    /**
     * @param EntityInterface $entity
     *
     * @return bool
     */
    public function isNew(EntityInterface $entity): bool
    {
        return $this->is($entity, UnitOfWork::STATE_NEW);
    }

    /**
     * @param EntityInterface $entity
     *
     * @return bool
     */
    public function isDetached(EntityInterface $entity): bool
    {
        return $this->is($entity, UnitOfWork::STATE_DETACHED);
    }

    /**
     * @param EntityInterface $entity
     *
     * @return bool
     */
    public function isRemoved(EntityInterface $entity): bool
    {
        return $this->is($entity, UnitOfWork::STATE_REMOVED);
    }

    /**
     * @param EntityInterface $entity
     * @param int             $state
     *
     * @return bool
     */
    public function is(EntityInterface $entity, int $state): bool
    {
        return $state === $this->getState($entity);
    }

    /**
     * @param EntityInterface $entity
     *
     * @return int
     */
    public function getState(EntityInterface $entity): int
    {
        return $this->getUnitOfWork()->getEntityState($entity);
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->manager;
    }

    /**
     * @return UnitOfWork
     */
    public function getUnitOfWork(): UnitOfWork
    {
        return $this->manager->getUnitOfWork();
    }
}
