<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Entity\Job;
use App\Entity\Manager\JobEntityManager;
use App\Helper\ValidationHelper;
use Psr\Log\LoggerInterface;

/**
 * Class JobService.
 */
class JobService extends AbstractService
{
    /**
     * @var JobEntityManager
     */
    private $entityManager;

    /**
     * JobService constructor.
     *
     * @param JobEntityManager $entityManager
     * @param LoggerInterface  $logger
     * @param ValidationHelper $validator
     */
    public function __construct(
        JobEntityManager $entityManager,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->entityManager = $entityManager;
        parent::__construct($validator, $logger);
    }

    /**
     * @param Job $job
     */
    public function save(Job $job): void
    {
        $this->entityManager->persist($job);
        $this->entityManager->flush();
    }
}
