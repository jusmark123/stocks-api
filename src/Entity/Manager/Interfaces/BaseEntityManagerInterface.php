<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Manager\Interfaces;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

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
     * @param EntityInterface $entity
     * @param bool            $flush
     *
     * @return EntityInterface
     */
    public function persist(EntityInterface $entity, bool $flush = false): EntityInterface;

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
     * @param EntityInterface $entity
     * @param bool            $flush
     *
     * @return EntityInterface
     */
    public function remove(EntityInterface $entity, bool $flush = false): EntityInterface;
}
