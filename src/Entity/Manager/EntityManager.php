<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\AbstractEntity;
use App\Entity\Interfaces\EntityInterface;
use App\Service\EntityStateService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class EntityManager implements BaseEntityManagerInterface
{
    public const ENTITY_CLASS = '';
    public const ENTITY_LISTENER_CLASS = '';

    /**
     * @var ManagerRegistry
     */
    private ManagerRegistry $doctrine;

    /**
     * @var EntityStateService
     */
    private EntityStateService $stateService;

    /**
     * @var string
     */
    private string $entityClass;

    /**
     * @var string
     */
    private string $entityListenerClass;

    /**
     * EntityManager constructor.
     *
     * @param ManagerRegistry    $doctrine
     * @param EntityStateService $stateService
     * @param string|null        $entityClass
     * @param string|null        $entityListenerClass
     */
    public function __construct(
        ManagerRegistry $doctrine,
        EntityStateService $stateService,
        ?string $entityClass = null,
        ?string $entityListenerClass = null
    ) {
        $this->doctrine = $doctrine;
        $this->stateService = $stateService;
        $this->entityClass = $entityClass ?? static::ENTITY_CLASS;
        $this->entityListenerClass = $entityListenerClass ?? static::ENTITY_LISTENER_CLASS;
    }

    /**
     * @return ObjectManager
     */
    public function getEntityManager(): ObjectManager
    {
        return $this->doctrine->getManagerForClass($this->entityClass);
    }

    /**
     * @return ObjectRepository
     */
    public function getEntityRepository(): ObjectRepository
    {
        return $this->getEntityManager()
            ->getRepository($this->entityClass);
    }

    /**
     * @return EntityStateService
     */
    public function getStateService(): EntityStateService
    {
        return $this->stateService;
    }

    /**
     * @return array
     */
    public function getEntityListener()
    {
        return $this->getEntityManager()
            ->getConfiguration()
            ->getEntityListenerResolver()
            ->resolve($this->entityListenerClass);
    }

    /**
     * @param EntityInterface $entity
     * @param bool            $flush
     *
     * @return EntityInterface
     */
    public function persist(EntityInterface $entity, bool $flush = false): EntityInterface
    {
        $state = $this->getStateService()->getState($entity);

        switch ($state) {
            case UnitOfWork::STATE_NEW:
            case UnitOfWork::STATE_DETACHED:
                $this->getEntityManager()->persist($entity);
                break;
        }

        if ($flush) {
            $this->flush();
        }

        return $entity;
    }

    /**
     * Flushed all changes to object that have been queued up to now to the database.
     *
     * @param bool $clear
     */
    public function flush(bool $clear = false): void
    {
        $this->getEntityManager()->flush();

        $clear && $this->clear();
    }

    /**
     * Clears the ObjectManager.
     * All objects that are currently managed by this ObjectManager become detached.
     */
    public function clear(): void
    {
        $this->getEntityManager()->clear();
    }

    public function refresh($objects): EntityManager
    {
        $objects = is_iterable($objects) ? $objects : [$objects];

        foreach ($objects as $object) {
            $this->getEntityManager()->refresh($object);
        }

        return $this;
    }

    /**
     * @param EntityInterface $entity
     */
    public function detach(EntityInterface $entity)
    {
        $this->getEntityManager()->clear($entity);
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
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): ArrayCollection
    {
        $entities = $this->getEntityRepository()->findBy($criteria, $orderBy, $limit, $offset);

        return new ArrayCollection($entities);
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
    public function remove(EntityInterface $entity, bool $flush = false): EntityInterface
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->flush();
        }

        return $entity;
    }

    /**
     * @param array $criteria
     * @param int   $pass
     */
    public function removeBy(array $criteria, int $pass = 20)
    {
        $entities = $this->findBy($criteria);

        $i = 1;

        foreach ($entities as $entity) {
            $this->remove($entity);
            0 === $i && $this->flush();
            ++$i;
        }

        $this->flush();
    }

    /**
     * @param string $tableName
     * @param bool   $cascade
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function truncate(string $tableName, bool $cascade = false)
    {
        $connection = $this->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        $connection->executeStatement($platform->getTruncateTableSQL($tableName, $cascade));
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->getEntityManager()->getConnection();
    }

    /**
     * @return array
     */
    public function getListeners(): array
    {
        return $this->getEntityManager()->getEventManager()->getListeners();
    }

    /**
     * @return ClassMetadata
     */
    public function getMetadataFor(): ClassMetadata
    {
        return $this->getEntityManager()->getClassMetadata($this->entityClass);
    }
}
