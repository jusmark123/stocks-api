<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\Manager\Interfaces\BaseEntityManagerInterface;
use App\Entity\Manager\Interfaces\EntityInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class AbstractEntityManager implements BaseEntityManagerInterface
{
    const ENTITY_CLASS = '';

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * AbstractEntityManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @return ObjectRepository
     */
    public function getEntityRepository()
    {
        return $this->entityManager->getRepository(static::ENTITY_CLASS);
    }

    /**
     * @param $entity
     * @param bool $flush
     *
     * @return mixed
     */
    public function persist($entity, bool $flush = false): EntityInterface
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }

        return $entity;
    }

    public function flush()
    {
        $this->entityManager->flush();
    }

    public function clear()
    {
        $this->entityManager->clear();
    }

    /**
     * @param EntityInterface $entity
     *
     * @return array
     */
    public function detach(EntityInterface $entity): array
    {
        $this->entityManager->detach($entity);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->getEntityRepository()->findAll();
    }

    /**
     * @param array $criteria
     *
     * @return mixed
     */
    public function findOneBy(array $criteria): array
    {
        return $this->getEntityRepository()->findOneBy($criteria);
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     *
     * @return array|EntityInterface[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getEntityRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->getEntityRepository()->find($id);
    }

    /**
     * @param EntityInterface $entity
     * @param bool            $flush
     *
     * @return EntityInterface
     */
    public function remove(EntityInterface $entity, bool $flush = false): EntityInterface
    {
        $this->entityManager->remove($entity);

        if ($flush) {
            $this->entityManager->flush();
        }

        return $entity;
    }
}
