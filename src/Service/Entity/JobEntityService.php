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
     * @var JobItemEntityService
     */
    private $jobItemDataService;

    /**
     * JobDataPersister constructor.
     *
     * @param DefaultTypeService     $defaultTypeService
     * @param EntityManagerInterface $entityManager
     * @param JobItemEntityService   $jobItemService
     * @param LoggerInterface        $logger
     * @param ValidationHelper       $validator
     */
    public function __construct(
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        JobItemEntityService $jobItemService,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->jobItemDataService = $jobItemService;
        parent::__construct($defaultTypeService, $entityManager, $logger, $validator);
    }

    /**
     * @return JobItemEntityService
     */
    public function getJobItemService()
    {
        return $this->jobItemDataService;
    }
}
