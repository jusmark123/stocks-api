<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\Account;

use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Constants\Transport\JobConstants;
use App\DataProvider\AbstractJobRequestDataProvider;
use App\Entity\Account;
use App\Entity\Job;
use App\Entity\Manager\AccountEntityManager;
use App\Entity\Source;
use App\Event\Job\JobInitiateFailedEvent;
use App\Exception\EmptyOrderHistoryException;
use App\Helper\ValidationHelper;
use App\JobHandler\Order\SyncOrderHistoryJobHandler;
use App\Service\Brokerage\BrokerageServiceProvider;
use App\Service\JobService;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AccountSyncOrderHistoryDataProvider.
 */
class AccountSyncOrderHistoryDataProvider extends AbstractJobRequestDataProvider
{
    const RESOURCE_CLASS = Account::class;
    const OPERATION_NAME = 'account_sync_orders';

    /**
     * @var BrokerageServiceProvider
     */
    private $brokerageServiceProvider;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * AccountSyncOrderHistoryDataProvider constructor.
     *
     * @param BrokerageServiceProvider $brokerageServiceProvider
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param JobService               $jobService
     * @param OrderService             $orderService
     * @param LoggerInterface          $logger
     * @param ValidationHelper         $validator
     */
    public function __construct(
        BrokerageServiceProvider $brokerageServiceProvider,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        JobService $jobService,
        OrderService $orderService,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->brokerageServiceProvider = $brokerageServiceProvider;
        $this->orderService = $orderService;
        parent::__construct($entityManager, $dispatcher, $jobService, $logger, $validator);
    }

    /**
     * @param string      $resourceClass
     * @param             $id
     * @param string|null $operationName
     * @param array       $context
     *
     * @throws EmptyOrderHistoryException
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return Job
     */
    public function createJob(string $resourceClass, $id, string $operationName = null, array $context = []): Job
    {
        $job = null;
        try {
            $account = $this->getAccount($id);
            $source = $this->getSource($context);
//            $job = $this->getJob($account,
//                SyncOrderHistoryJobHandler::getJobName(), [
//                JobConstants::JOB_INITIATED,
//                JobConstants::JOB_PENDING,
//                JobConstants::JOB_QUEUED,
//                JobConstants::JOB_CREATED,
//            ]);

            if ($job instanceof Job) {
                return $job;
            }

            $filters = $this->validateFilters($account, $context);
            $orderHistory = $this->orderService->getOrderHistory($account, $filters);

            if (empty($orderHistory)) {
                throw new EmptyOrderHistoryException('No orders returned');
            }

            $job = $this->jobService->createJob(
                SyncOrderHistoryJobHandler::JOB_NAME,
                SyncOrderHistoryJobHandler::JOB_DESCRIPTION,
                $orderHistory,
                null,
                $account,
                $source
            );
        } catch (\Exception $e) {
            if ($job instanceof Job) {
                $job->setErrorMessage($e->getMessage())
                    ->setErrorTrace($e->getTraceAsString())
                    ->setStatus(JobConstants::JOB_FAILED);
                $this->dispatch(new JobInitiateFailedEvent($job, $e));
            }

            throw $e;
        }

        return $job;
    }

    /**
     * @param Account $account
     * @param array   $context
     *
     * @return array|mixed
     */
    private function validateFilters(Account $account, array $context = []): array
    {
        if (!isset($context['filters'])) {
            return [];
        }

        $constantsClass = $this->brokerageServiceProvider
            ->getBrokerageService($account->getBrokerage())
            ->getConstantsClass();

        parent::checkFilters($context, $constantsClass);

        return $context['filters'];
    }

    /**
     * @param array $context
     *
     * @return Source|null
     */
    private function getSource(array $context): ?Source
    {
        $source = null;
        if (\array_key_exists('source_id', $context['subresource_identifiers']) &&
            'undefined' !== $context['subresource_identifiers']['source_id']) {
            $source = $this->entityManager
                ->getRepository(Source::class)
                ->findOneBy(['guid' => $context['subresource_identifiers']['source_id']]);
        }

        return $source;
    }

    /**
     * @param $id
     *
     * @return Account
     */
    private function getAccount($id): Account
    {
        $account = $this->entityManager
            ->getRepository(Account::class)
            ->findOneBy(['guid' => $id]);

        if (!$account instanceof Account) {
            throw new ItemNotFoundException(AccountEntityManager::ACCOUNT_NOT_FOUND);
        }

        return $account;
    }
}
