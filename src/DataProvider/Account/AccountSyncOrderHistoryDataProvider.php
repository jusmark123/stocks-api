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
use App\Exception\EmptyOrderHistoryException;
use App\Helper\ValidationHelper;
use App\JobHandler\Order\SyncOrderHistoryJobHandler;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Exception\InvalidMessage;
use App\MessageClient\Exception\PublishException;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\Brokerage\BrokerageServiceProvider;
use App\Service\DefaultTypeService;
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
     * @var DefaultTypeService
     */
    private $defaultTypeService;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * AccountSyncOrderHistoryDataProvider constructor.
     *
     * @param BrokerageServiceProvider $brokerageServiceProvider
     * @param DefaultTypeService       $defaultTypeService
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param JobService               $jobService
     * @param OrderService             $orderService
     * @param LoggerInterface          $logger
     * @param MessageFactory           $messageFactory
     * @param ClientPublisher          $publisher
     * @param ValidationHelper         $validator
     */
    public function __construct(
        BrokerageServiceProvider $brokerageServiceProvider,
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        JobService $jobService,
        OrderService $orderService,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        ClientPublisher $publisher,
        ValidationHelper $validator
    ) {
        $this->brokerageServiceProvider = $brokerageServiceProvider;
        $this->defaultTypeService = $defaultTypeService;
        $this->orderService = $orderService;
        parent::__construct(
            $entityManager,
            $dispatcher,
            $jobService,
            $logger,
            $messageFactory,
            $publisher,
            $validator
        );
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
     * @throws InvalidMessage
     * @throws PublishException|\Exception
     *
     * @return Job
     */
    public function createJob(string $resourceClass, $id, string $operationName = null, array $context = []): Job
    {
        xdebug_break();
        $job = null;
        $account = $this->getAccount($id);
        $source = $this->getSource($context);
        $job = $this->getJob(
            $account,
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

        $filters = $this->validateFilters($account, $context);

        $job = $this->jobService->createJob(
            SyncOrderHistoryJobHandler::JOB_NAME,
            SyncOrderHistoryJobHandler::JOB_DESCRIPTION,
            $filters,
            null,
            null,
            $account,
            $source
        );

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

        if (!$source instanceof Source) {
            $source = $this->defaultTypeService->getDefaultSource();
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
