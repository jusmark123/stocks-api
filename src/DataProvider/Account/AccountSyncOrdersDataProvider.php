<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\Account;

use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Constants\Entity\SourceConstants;
use App\Constants\Entity\UserConstants;
use App\Constants\Transport\JobConstants;
use App\Constants\Transport\Queue;
use App\DataProvider\AbstractJobRequestDataProvider;
use App\Entity\Account;
use App\Entity\Factory\JobFactory;
use App\Entity\Job;
use App\Entity\Manager\AccountEntityManager;
use App\Entity\Source;
use App\Entity\User;
use App\Event\Job\JobInitiatedEvent;
use App\Event\Job\JobInitiateFailedEvent;
use App\Event\Job\JobProcessFailedEvent;
use App\Event\OrderInfo\OrderInfoPublishFailedEvent;
use App\Exception\EmptyOrderHistoryException;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Exception\PublishException;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\AccountService;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

/**
 * Class AccountSyncOrdersDataProvider.
 */
class AccountSyncOrdersDataProvider extends AbstractJobRequestDataProvider
{
    protected const RESOURCE_CLASS = Account::class;
    protected const OPERATION_NAME = 'account_sync_orders';

    const JOB_TOPIC_NAME = Queue::JOB_PERSISTENT_ROUTING_KEY;
    const ORDER_INFO_TOPIC_NAME = Queue::ORDER_INFO_PERSISTENT_ROUTING_KEY;

    const HEADERS = [
        Queue::SYSTEM_PUBLISHER_HEADER_NAME => Queue::SYSTEM_PUBLISHER_NAME,
        JobConstants::REQUEST_HEADER_NAME => JobConstants::REQUEST_SYNC_ORDER_REQUEST,
    ];

    /** @var AccountService */
    private $accountService;

    /** @var ClientPublisher */
    private $clientPublisher;

    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var EntityManager */
    private $entityManager;

    /** @var LoggerInterface */
    private $logger;

    /** @var MessageFactory */
    private $messageFactory;

    /** @var RequestStack */
    private $requestStack;

    /**
     * AccountSyncOrdersDataProvider constructor.
     *
     * @param EntityManager            $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param AccountService           $accountService
     * @param ClientPublisher          $clientPublisher
     * @param LoggerInterface          $logger
     * @param MessageFactory           $messageFactory
     * @param RequestStack             $requestStack
     */
    public function __construct(
        EntityManager $entityManager,
        EventDispatcherInterface $dispatcher,
        AccountService $accountService,
        ClientPublisher $clientPublisher,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        RequestStack $requestStack
    ) {
        $this->dispatcher = $dispatcher;
        $this->entityManager = $entityManager;
        $this->accountService = $accountService;
        $this->clientPublisher = $clientPublisher;
        $this->messageFactory = $messageFactory;
        $this->logger = $logger;
        $this->requestStack = $requestStack;
    }

    /**
     * @param string $resourceClass
     * @param $id
     * @param string|null $operationName
     * @param array       $context
     *
     * @throws \App\MessageClient\Exception\InvalidMessage
     *
     * @return Job
     */
    public function createJob(string $resourceClass, $id, string $operationName = null, array $context = []): Job
    {
        $job = null;
        try {
            $systemSource = $this->entityManager->getRepository(Source::class)->findOneBy(
                ['guid' => SourceConstants::SYSTEM_SOURCE_GUID]);

            $systemUser = $this->entityManager->getRepository(User::class)->findOneBy(
                ['guid' => UserConstants::SYSTEM_USER_GUID]);

            $account = $this->entityManager->getRepository(Account::class)->findOneBy(['guid' => $id]);

            if (!$account instanceof Account) {
                throw new ItemNotFoundException(AccountEntityManager::ACCOUNT_NOT_FOUND);
            }

            $job = $this->entityManager->getRepository(Job::class)->findOneBy([
                'account' => $account,
                'name' => JobConstants::REQUEST_SYNC_ORDER_REQUEST,
                'status' => [JobConstants::JOB_INITIATED, JobConstants::JOB_PENDING],
            ]);

            if ($job instanceof Job) {
                return $job;
            }

            $this->accountService->setBrokerageService($account->getBrokerage());

            $filters = $this->validateFilters($account, $context);

            $orderHistory = $this->accountService->getOrderHistory($account, $filters);

            if (empty($orderHistory)) {
                throw new EmptyOrderHistoryException('No orders returned');
            }

            $orderIds = array_column($orderHistory, 'id');
            $data = array_combine($orderIds, array_fill(0, \count($orderIds), JobConstants::JOB_PENDING));

            // Create Job
            $job = JobFactory::create()
                ->setAccount($account)
                ->setName(JobConstants::REQUEST_SYNC_ORDER_REQUEST)
                ->setDescription(sprintf(JobConstants::ACCOUNT_SYNC_ORDERS_REQUEST, $account->getGuid()))
                ->setData($data)
                ->setSource($systemSource)
                ->setUser($systemUser);

            $this->dispatcher->dispatch(
                new JobInitiatedEvent($job),
                JobInitiatedEvent::getEventName()
            );

            $headers = array_merge(self::HEADERS, [
                JobConstants::JOB_ID_HEADER_NAME => $job->getGuid()->toString(),
            ]);

            try {
                foreach ($orderHistory as $orderInfo) {
                    try {
                        $packet = $this->messageFactory->createPacket(
                            self::ORDER_INFO_TOPIC_NAME,
                            json_encode($orderInfo),
                            $headers
                        );
                        $this->clientPublisher->publish($packet);
                    } catch (PublishException $e) {
                        $this->dispatcher->dispatch(
                            new OrderInfoPublishFailedEvent($orderInfo, $e),
                            OrderInfoPublishFailedEvent::getEventName()
                        );
                    }
                }
            } catch (\Exception $e) {
                $this->dispatcher->dispatch(
                    new JobProcessFailedEvent($job, $e),
                    JobProcessFailedEvent::getEventName()
                );
            }
        } catch (\Exception $e) {
            if ($job instanceof Job) {
                $this->dispatcher->dispatch(
                    new JobInitiateFailedEvent($job, $e),
                    JobInitiateFailedEvent::getEventName()
                );

                $job->setError($e->getMessage())
                    ->setErrorTrace($e->getTraceAsString())
                    ->setStatus(JobConstants::JOB_FAILED);
            }
        }

        return $job;
    }

    /**
     * @param Account $account
     * @param array   $context
     *
     * @return array|mixed
     */
    private function validateFilters(Account $account, $context = [])
    {
        try {
            $constantsClass = $this->accountService->getBrokerageService()->getConstantsClass();
            $constantsClass = new \ReflectionClass($constantsClass);
            $constants = $constantsClass->getConstants();
            $filtersConstants = $constants['ORDERS_FILTERS_DATATYPE'];

            if (!isset($context['filters'])) {
                return [];
            }

            if (isset($context['filters'])) {
                foreach ($context['filters'] as $key => $filter) {
                    if (\array_key_exists('ORDERS_'.strtoupper($key).'_ENUM', $constants)) {
                        $enums = $constants['ORDERS_'.strtoupper($key).'_ENUM'];
                        if (!\in_array($filter, $enums, true)) {
                            throw new InvalidArgumentException(
                                sprintf('Invalid filter %s provided for: %s', $filter, $key)
                            );
                        }
                    }
                    if (\array_key_exists($key, $filtersConstants)) {
                        if (!filter_var($filter, $filtersConstants[$key])) {
                            throw new InvalidArgumentException(
                                sprintf('Invalid datatype provided for filter: %s', $key)
                            );
                        }
                    }
                }
            }

            return $context['filters'];
        } catch (\Exception $e) {
            $this->logger->error(self::class, [
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
