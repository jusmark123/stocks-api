<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\AbstractEntity;
use App\Entity\Manager\Interfaces\BaseEntityManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

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
    public function getEntityRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(static::ENTITY_CLASS);
    }

    /**
     * @param $entity
     * @param bool $flush
     *
     * @return mixed
     */
    public function persist($entity, bool $flush = false): AbstractEntity
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
     * @param AbstractEntity $entity
     *
     * @return array
     */
    public function detach(AbstractEntity $entity): array
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
    public function findOneBy(array $criteria)
    {
        return $this->getEntityRepository()->findOneBy($criteria);
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     *
     * @return array|AbstractEntity[]
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
     * @param AbstractEntity $entity
     * @param bool           $flush
     *
     * @return AbstractEntity
     */
    public function remove(AbstractEntity $entity, bool $flush = false): AbstractEntity
    {
        $this->entityManager->remove($entity);

        if ($flush) {
            $this->entityManager->flush();
        }

        return $entity;
    }
}
