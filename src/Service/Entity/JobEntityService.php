<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Entity;

use App\Helper\ValidationHelper;
use App\Service\DefaultTypeService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class JobEntityService.
 */
class JobEntityService extends AbstractEntityService
{
    /**
     * @var JobDataItemEntityService
     */
    private $jobItemDataService;

    /**
     * JobDataPersister constructor.
     *
     * @param DefaultTypeService       $defaultTypeService
     * @param EntityManagerInterface   $entityManager
     * @param JobDataItemEntityService $jobDataItemService
     * @param LoggerInterface          $logger
     * @param ValidationHelper         $validator
     */
    public function __construct(
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        JobDataItemEntityService $jobDataItemService,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->jobItemDataService = $jobDataItemService;
        parent::__construct($defaultTypeService, $entityManager, $logger, $validator);
    }

    /**
     * @return JobDataItemEntityService
     */
    public function getJobDataItemService()
    {
        return $this->jobItemDataService;
    }
}
