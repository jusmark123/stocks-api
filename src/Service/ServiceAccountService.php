<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Entity\Manager\AccountEntityManager;
use App\Helper\ValidationHelper;
use Psr\Log\LoggerInterface;

/**
 * Class ServiceAccountService.
 */
class ServiceAccountService extends AbstractService
{
    /**
     * ServiceAccountService constructor.
     *
     * @param AccountEntityManager $entityManager
     * @param LoggerInterface      $logger
     * @param ValidationHelper     $validator
     */
    public function __construct(
        AccountEntityManager $entityManager,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $validator, $logger);
    }
}
