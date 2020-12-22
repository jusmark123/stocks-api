<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\AbstractEntity;
use App\Entity\Job;
use App\Event\AbstractEvent;
use App\Helper\ValidationHelper;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Listener\MessageListener;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

/**
 * Class AbstractJobRequestDataProvider.
 */
abstract class AbstractJobRequestDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface, LoggerAwareInterface
{
    use JobRequestDataProviderTrait;
    use LoggerAwareTrait;

    protected const JOB_TOPIC_NAME = '';
    protected const OPERATION_NAME = '';
    protected const RESOURCE_CLASS = '';

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var JobService
     */
    protected $jobService;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var ClientPublisher
     */
    protected $publisher;

    /**
     * @var ValidationHelper
     */
    protected $validator;

    /**
     * AbstractJobRequestDataProvider constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param JobService               $jobService
     * @param LoggerInterface          $logger
     * @param MessageListener          $messageFactory
     * @param ClientPublisher          $publisher
     * @param ValidationHelper         $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        JobService $jobService,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        ClientPublisher $publisher,
        ValidationHelper $validator
    ) {
        $this->setLogger($logger);
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
        $this->jobService = $jobService;
        $this->messageFactory = $messageFactory;
        $this->publisher = $publisher;
        $this->validator = $validator;
    }

    /**
     * @return string
     */
    protected function getResourceClass(): string
    {
        return static::RESOURCE_CLASS;
    }

    /**
     * @return string
     */
    protected function getOperationName(): string
    {
        return static::OPERATION_NAME;
    }

    /**
     * @param string           $resourceClass
     * @param array|int|string $id
     * @param string|null      $operationName
     * @param array            $context
     *
     * @return Job
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): Job
    {
        return $this->createJob($resourceClass, $id, $opertaionName = null, $context);
    }

    /**
     * @param        $account
     * @param string $jobName
     * @param array  $statuses
     *
     * @return Job|null
     */
    protected function getJob($account, string $jobName, array $statuses): ?Job
    {
        return $this->entityManager
            ->getRepository(Job::class)
            ->findOneBy([
                'account' => $account,
                'name' => $jobName,
                'status' => $statuses,
            ]);
    }

    /**
     * @param AbstractEntity $entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function save(AbstractEntity $entity)
    {
        $this->validator->validate($entity);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param AbstractEvent $event
     */
    protected function dispatch(AbstractEvent $event)
    {
        $this->dispatcher->dispatch($event, $event::getEventName());
    }

    /**
     * @param array  $context
     * @param string $constantsClass
     *
     * @throws \ReflectionException
     */
    protected function checkFilters(array $context, string $constantsClass)
    {
        $constantsClass = new \ReflectionClass($constantsClass);
        $constants = $constantsClass->getConstants();
        $filterConstants = $constants['ORDERS_FILTERS_DATATYPE'];

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
                if (\array_key_exists($key, $filterConstants)) {
                    if (!filter_var($filter, $filterConstants[$key])) {
                        throw new InvalidArgumentException(
                            sprintf('Invalid datatype provided for filter: %s', $key)
                        );
                    }
                }
            }
        }
    }
}
