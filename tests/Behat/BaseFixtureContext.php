<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class BaseFixtureContext.
 */
class BaseFixtureContext extends AbstractContext
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected $referenceRepository;

    /**
     * BaseFixtureContext constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $name
     * @param $object
     */
    public function setReference($name, $object)
    {
        $this->referenceRepository[$name] = $object;
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function getReference($name)
    {
        if (!\array_key_exists($name, $this->referenceRepository)) {
            return null;
        }

        return $this->referenceRepository[$name];
    }

    /**
     * @param string $class
     * @param string $field
     * @param string $value
     *
     * @return object|null
     */
    public function findOneBy(string $class, string $field, string $value)
    {
        $entityRepo = $this->entityManager->getRepository($class);

        return $entityRepo->findOneBy([$field => $value]);
    }

    /**
     * @param $entity
     */
    public function persist($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
