<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Entity;

use App\Entity\AbstractEntity;
use App\Entity\Job;
use App\Entity\JobItem;
use App\Helper\ValidationHelper;
use App\Service\DefaultTypeService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class JobItemEntityService.
 */
class JobItemEntityService extends AbstractEntityService
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * JobItemEntityService constructor.
     *
     * @param DefaultTypeService       $defaultTypeService
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @param ValidationHelper         $validator
     */
    public function __construct(
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->dispatcher = $dispatcher;
        parent::__construct($defaultTypeService, $entityManager, $logger, $validator);
    }

    /**
     * @param     $data
     * @param Job $job
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return AbstractEntity
     */
    public function create($data, Job $job): JobItem
    {
    }

    /**
     * @param JobItem    $jobItem
     * @param \Exception $exception
     *
     *@throws OptimisticLockException
     * @throws ORMException
     *
     * @return JobItem
     */
    public function setError(JobItem $jobItem, \Exception $exception)
    {
        $jobItem
            ->setErrorMessage($exception->getMessage())
            ->setErrorTrace($exception->getTraceAsString());
        $this->save($jobItem);

        return $jobItem;
    }

    /**
     * @param JobItem $jobItem
     * @param string  $status
     *
     *@throws OptimisticLockException
     * @throws ORMException
     *
     * @return JobItem
     */
    public function setStatus(JobItem $jobItem, string $status)
    {
        $jobItem->setStatus($status);
        $this->save($jobItem);

        return $jobItem;
    }
}
