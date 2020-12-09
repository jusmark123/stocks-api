<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Entity\Manager\JobEntityManager;
use App\Helper\ValidationHelper;
use Psr\Log\LoggerInterface;

/**
 * Class JobService.
 */
class JobService extends AbstractService
{
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
        parent::__construct($entityManager, $validator, $logger);
    }
}
