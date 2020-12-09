<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager;

use App\Entity\AbstractEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class BaseEntityManagerInterface.
 */
interface BaseEntityManagerInterface
{
    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager();

    /**
     * @return ObjectRepository
     */
    public function getEntityRepository();

    /**
     * @param AbstractEntity $entity
     * @param bool           $flush
     *
     * @return AbstractEntity
     */
    public function persist(AbstractEntity $entity, bool $flush = false): AbstractEntity;

    public function flush();

    public function clear();

    /**
     * @return mixed
     */
    public function findAll();

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     *
     * @return mixed
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * @param array $criteria
     *
     * @return mixed
     */
    public function findOneBy(array $criteria);

    /**
     * @param $id
     *
     * @return object|null
     */
    public function find($id);

    /**
     * @param AbstractEntity $entity
     * @param bool           $flush
     *
     * @return AbstractEntity
     */
    public function remove(AbstractEntity $entity, bool $flush = false): AbstractEntity;
}
