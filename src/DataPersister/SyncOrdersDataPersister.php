<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Constants\Transport\JobConstants;
use App\DTO\SyncOrdersRequest;
use App\Entity\Job;
use App\Entity\Manager\AccountEntityManager;
use App\JobHandler\Order\SyncOrderHistoryJobHandler;
use App\Service\Brokerage\BrokerageServiceProvider;
use App\Service\DefaultTypeService;
use App\Service\JobService;

/**
 * Class SyncOrdersDataPersister.
 */
class SyncOrdersDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var AccountEntityManager
     */
    private $accountEntityManager;

    /**
     * @var BrokerageServiceProvider
     */
    private $brokerageServiceProvider;

    /**
     * @var DefaultTypeService
     */
    private $defaultTypeService;

    /**
     * @var JobService
     */
    private $jobService;

    /**
     * SyncOrdersDataPersister constructor.
     *
     * @param AccountEntityManager     $accountEntityManager
     * @param BrokerageServiceProvider $brokerageServiceProvider
     * @param DefaultTypeService       $defaultTypeService
     * @param JobService               $jobService
     */
    public function __construct(
        AccountEntityManager $accountEntityManager,
        BrokerageServiceProvider $brokerageServiceProvider,
        DefaultTypeService $defaultTypeService,
        JobService $jobService
    ) {
        $this->brokerageServiceProvider = $brokerageServiceProvider;
        $this->defaultTypeService = $defaultTypeService;
        $this->accountEntityManager = $accountEntityManager;
        $this->jobService = $jobService;
    }

    /**
     * @param       $data
     * @param array $context
     *
     * @return bool
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof SyncOrdersRequest;
    }

    /**
     * @param SyncOrdersRequest $data
     * @param array             $context
     *
     * @throws \Exception
     *
     * @return Job|object|void
     */
    public function persist($data, array $context = [])
    {
        $job = null;
        $account = $data->getAccount();
        $source = $data->getSource() ?? $this->defaultTypeService->getDefaultSource();
        $job = $this->jobService->getJob($account,
            SyncOrderHistoryJobHandler::JOB_NAME,
            [
                JobConstants::JOB_INITIATED,
                JobConstants::JOB_PENDING,
                JobConstants::JOB_QUEUED,
                JobConstants::JOB_CREATED,
            ]
        );

        if ($job instanceof Job) {
            return $job;
        }

        $job = $this->jobService->createJob(
            SyncOrderHistoryJobHandler::JOB_NAME,
            SyncOrderHistoryJobHandler::JOB_DESCRIPTION,
            $data->getFilters(),
            null,
            null,
            $account,
            $source
        );

        return $job;
    }

    /**
     * @param       $data
     * @param array $context
     */
    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
    }
}
