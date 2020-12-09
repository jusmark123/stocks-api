<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Tests\Behat\Traits\KernelContructorAwareTrait;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class DatabaseContext extends AbstractContext
{
    use KernelContructorAwareTrait;

    private $ormPurger;

    private $ormExecutor;

    private $manager;

    public function clearData()
    {
        $conn = $this->getEntityManager()->getConnection();
        $conn->executeQuery('SET FOREIGN_KEY_CHECKS=0');

        $purger = $this->getOrmPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();

        $conn->executeQuery('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * @Given I purge the database
     */
    public function iPurgeTheDatabase()
    {
        $this->clearData();
    }

    /**
     * @Then the field :field in :entity should equal :value
     *
     * @param $field
     * @param $entity
     * @param $value
     *
     * @throws \AssertionError
     */
    public function theFieldShouldEqual($field, $entity, $value)
    {
        $this->disableSoftdeleteFilter();

        $result = $this->getRepository($entity)->findOneBy([$field => $value]);

        if (null === $result) {
            throw new \AssertionError(sprintf(
                'Row doesn\'t exist for entity "%s" field "%s" with value "%s"',
                $entity,
                $field,
                $value
            ));
        }

        $this->enableSoftdeleteFilter();
    }

    /**
     * @Then the row in :entity with :field equal to :value has been hard deleted
     *
     * @param $entity
     * @param $field
     * @param $value
     */
    public function theRowInWithEqualToHasBeenHardDeleted($entity, $field, $value)
    {
        $this->disableSoftdeleteFilter();

        $result = $this->getRepository($entity)->findOneBy([$field => $value]);

        if (null !== $result) {
            throw new \AssertionError(sprintf(
               'A row exists for entity "%s" field "%s" field "%s" with value "%s"',
               $entity,
               $field,
               $value
            ));
        }

        $this->enableSoftdeleteFilter();
    }

    /**
     * @Then the row in :entity with :field equal to :value has been soft deleted
     *
     * @param $entity
     * @param $field
     * @param $values
     *
     * @throws \AssertionError
     */
    public function theRowInWithEqualToHasBeenSoftDeleted($entity, $field, $value)
    {
        $this->disableSoftdeleteFilter();

        $result = $this->getRepository($entity)->findOneBy([$field => $value]);

        if (null === $result) {
            throw new \AssertionError(sprintf(
                'Row doesn\'t exist for entity "%s" field "%s" with value "%s"',
                $entity,
                $field,
                $value
            ));
        }

        try {
            $deactivatedAt = $result->getDeactivatedAt();
        } catch (\Throwable $e) {
            throw new \AssertionError('Entity does not have a "getDeactivatedAt()" function');
        }

        try {
            if (!$deactivatedAt instanceof \DateTime) {
                throw new \Exception();
            }
        } catch (\Throwable $e) {
            throw new \AssertionError(sprintf(
              'An active row exists for entity "%s" field "%s" with the value "%s"',
              $entity,
              $field,
              $value
            ));
        }

        $this->enableSoftdeleteFilter();
    }

    /**
     * @Then the table :table with id :id should be deactivated by :userId
     *
     * @param string $table
     * @param string $userId
     * @param int    $id
     *
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function theDatabaseWithIdShouldBeDeactivatedBy(string $table, string $userId, int $id = 1): void
    {
        $sql = sprintf('select t.deactivated_at, t.deactivated_by FROM %s t WHERE t.id = %d', $table, $id);

        $entityManager = $this->getEntityManager();
        $stmt = $entityManager->getConnection()->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch();

        if (isset($result['deactivated_at']) && strtotime($result['deactivated_at']) > 0
            && isset($result['deactivated_by']) && $result['deactivated_by'] === $userId) {
            return;
        }

        throw new \Exception(
            'The deactivatedAt or deactivatedBy field was not populated in the database during softdelete'
        );
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        if (null === $this->manager) {
            $this->manager = $this->getContainer()->get('doctrine')->getManager();
        }

        return $this->manager;
    }

    /**
     * @return ORMPurger
     */
    public function getOrmPurger(): OrMPurger
    {
        if (!$this->ormPurger instanceof ORMPurger) {
            $fixturesLoad = $this->getContainer()->getParameter('stocks_api_doctrine');

            $this->ormPurger = new ORMPurger($this->getEntityManager(), $fixturesLoad['fixtures_load']['exclude_from_purge']);
        }

        return $this->ormPurger;
    }

    /**
     * @return ORMExecutor
     */
    public function getOrmExecutor(): ORMExecutor
    {
        if (!$this->ormExecutor instanceof OrmExecutor) {
            $this->ormExecutor = new ORMExecutor($this->getEntityManager(), $this->getOrmPurger());
        }

        return $this->ormExecutor;
    }

    /**
     * @param string $className
     *
     * @return ObjectRepository
     */
    public function getRepository(string $className)
    {
        try {
            $this->getEntityManager()->clear($className);
            $repository = $this->getEntityManager()->getRepository($className);
            if (!$repository instanceof ObjectRepository) {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            throw new \AssertionError(sprintf('Unable to find Entity Repository for "%s"', $className));
        }

        return $repository;
    }

    /**
     * @throws \AssertionError
     */
    public function disableSoftdeleteFilter()
    {
        try {
            $this->getEntityManager()->getFilters()->disable('softdeleteable');
        } catch (\Throwable $e) {
            throw new \AssertionError(sprintf('SoftDelete filter can\'t be disabled. Error: %s', $e->getMessage()));
        }
    }

    /**
     * @throws \AssertionError
     */
    public function enableSoftdeleteFilter()
    {
        try {
            $this->getEntityManager()->getFilters()->enable('softdeleteable');
        } catch (\Throwable $e) {
            throw new \AssertionError(sprintf('SoftDelete Filter can\'t be enabled. Error %s', $e->getMessage()));
        }
    }
}
