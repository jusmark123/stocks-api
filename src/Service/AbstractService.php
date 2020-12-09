<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Entity\AbstractEntity;
use App\Entity\Manager\BaseEntityManagerInterface;
use App\Helper\ValidationHelper;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractService.
 */
abstract class AbstractService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var BaseEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ValidationHelper
     */
    protected $validator;

    /**
     * AbstractService constructor.
     *
     * @param BaseEntityManagerInterface $entityManager
     * @param ValidationHelper           $validator
     * @param LoggerInterface            $logger
     */
    public function __construct(
        BaseEntityManagerInterface $entityManager,
        ValidationHelper $validator,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * @param ValidationHelper $validator
     */
    public function setValidator(ValidationHelper $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return BaseEntityManagerInterface
     */
    public function getEntityManager(): BaseEntityManagerInterface
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

    public function save(AbstractEntity $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
