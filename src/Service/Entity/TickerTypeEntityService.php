<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Entity;

use App\Entity\Manager\TickerTypeEntityManager;
use App\Helper\ValidationHelper;
use App\Service\AbstractService;
use Psr\Log\LoggerInterface;

class TickerTypeEntityService extends AbstractService
{
    public function __construct(
        TickerTypeEntityManager $entityManager,
        ValidationHelper $validator,
        LoggerInterface $logger
    ) {
        parent::__construct($entityManager, $validator, $logger);
    }
}
