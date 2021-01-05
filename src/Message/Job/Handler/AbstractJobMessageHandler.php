<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job\Handler;

use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Constants\Transport\JobConstants;
use App\Entity\Job;
use App\Entity\JobItem;
use App\Event\Job\JobCompleteEvent;
use App\Event\Job\JobProcessedEvent;
use App\Event\Job\JobProcessFailedEvent;
use App\Event\Job\JobProcessingEvent;
use App\Event\Job\JobReceiveFailedEvent;
use App\Event\JobItem\JobItemCancelledEvent;
use App\Event\JobItem\JobItemProcessedEvent;
use App\Event\JobItem\JobItemProcessFailedEvent;
use App\Event\JobItem\JobItemProcessingEvent;
use App\Event\JobItem\JobItemReceivedEvent;
use App\Event\JobItem\JobItemReceiveFailedEvent;
use App\Exception\JobCancelledException;
use App\Exception\MessageProcessedException;
use App\Message\Job\JobMessageInterface;
use App\Message\Job\JobRequestMessageInterface;
use App\Message\Job\Stamp\JobItemStamp;
use App\Service\JobService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Predis\Client;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class AbstractJobMessageHandler.
 */
abstract class AbstractJobMessageHandler extends AbstractMessageHandler implements LoggerAwareInterface
{
    use HandleTrait;
    use LoggerAwareTrait;

    /**
     * @var Client
     */
    protected $cache;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var Job
     */
    protected $job;

    /**
     * @var JobService
     */
    protected $jobService;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * AbstractJobMessageHandler constructor.
     *
     * @param Client                 $cache
     * @param EntityManagerInterface $entityManager
     * @param MessageBusInterface    $messageBus
     * @param JobService             $jobService
     * @param LoggerInterface        $logger
     * @param UserService            $userService
     */
    public function __construct(
        Client $cache,
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus,
        JobService $jobService,
        LoggerInterface $logger,
        UserService $userService
    ) {
        $this->cache = $cache;
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
        $this->jobService = $jobService;
        $this->logger = $logger;
        $this->userService = $userService;
    }

    public function isJobCancelled()
    {
        if (JobConstants::JOB_CANCELLED === $this->job->getStatus()) {
            throw new JobCancelledException();
        }
    }

    /**
     * @param JobRequestMessageInterface $requestMessage
     * @param callable                   $callback
     * @param                            $messageClass
     *
     * @return Job
     */
    public function parseJobRequest(
        JobRequestMessageInterface $requestMessage,
        callable $callback,
        $messageClass
    ): ?Job {
        try {
            $this->job = $this->entityManager
                ->getRepository(Job::class)
                ->findOneBy(['guid' => $requestMessage->getJobId()]);

            $job = $this->entityManager
                ->getRepository(Job::class)
                ->findOneBy([
                    'name' => $requestMessage->getJobName(),
                    'account' => $this->job->getAccount(),
                    'source' => $this->job->getSource(),
                ]);

            if ($job instanceof Job && $job->getGuid() !== $this->job->getGuid()) {
                $this->job->setStatus(JobConstants::JOB_CANCELLED);
            }

            $this->isJobCancelled();

            if (!$this->job instanceof Job) {
                throw new ItemNotFoundException('Job not found');
            }

            if (JobConstants::JOB_PROCESSED !== $this->job->getStatus()) {
                $this->jobService->dispatch(new JobProcessingEvent($this->job));
                $this->job = $callback($requestMessage->getRequest(), $this->job);
                $this->jobService->dispatch(new JobProcessedEvent($this->job));
            }

            $jobItems = $this->job->getJobItems();

            foreach ($jobItems as $key => $jobItem) {
                $envelope = (new Envelope($messageClass::create(
                    $this->job->getGuid()->toString(),
                    $jobItem->getGuid()->toString(),
                    $jobItem->getData()
                )))->with(new JobItemStamp($this->job->getGuid()->toString(), $jobItem->getGuid()->toString()));

                $this->getMessageBus()->dispatch($envelope);
                $this->setLastJobItem($jobItem, $jobItems);

                $this->isJobCancelled();
            }
        } catch (JobCancelledException $e) {
            if (isset($jobItems) && isset($jobItem)) {
                $this->setLastJobItem($jobItem, $jobItems, true);
            }

            return $this->job;
        } catch (ItemNotFoundException $e) {
            $this->jobService->dispatch(new JobReceiveFailedEvent($e, $this->job));
            throw $e;
        } catch (\Exception $e) {
            $this->jobService->dispatch(new JobProcessFailedEvent($e, $this->job));
            throw new UnrecoverableMessageHandlingException();
        }

        return $this->job;
    }

    public function parseJobItemRequest(
        JobMessageInterface $message,
        callable $callback,
        $entityParser
    ): JobItem {
        try {
            /** @var JobItem $jobItem */
            $jobItem = $this->entityManager
                ->getRepository(JobItem::class)
                ->findOneBy(['guid' => $message->getJobItemId()]);

            if (!$jobItem instanceof JobItem) {
                throw new ItemNotFoundException('JobItem not found');
            }

            $this->job = $jobItem->getJob();
            $this->isJobCancelled();

            $this->jobService->dispatch(new JobItemReceivedEvent($jobItem));
            $entityParser($jobItem, $this->job);

            $this->jobService->dispatch(new JobItemProcessingEvent($jobItem));
            $callback($message->getMessage(), $this->job);
            $this->jobService->dispatch(new JobItemProcessedEvent($jobItem));
        } catch (MessageProcessedException $e) {
            $this->jobService->dispatch(new JobItemProcessedEvent($jobItem));
        } catch (JobCancelledException $e) {
            $this->jobService->dispatch(new JobItemCancelledEvent($jobItem));
            throw new UnrecoverableMessageHandlingException('Job Cancelled');
        } catch (ItemNotFoundException $e) {
            $this->jobService->dispatch(new JobItemReceiveFailedEvent($e, $jobItem));
            throw new UnrecoverableMessageHandlingException('Job Cancelled');
        } catch (HandlerFailedException $e) {
            $this->jobService->dispatch(new JobItemProcessFailedEvent($e, $jobItem));
            throw $e;
        } catch (\Exception $e) {
            $this->jobService->dispatch(new JobItemProcessFailedEvent($e, $jobItem));
            throw new UnrecoverableMessageHandlingException($e->getMessage());
        }

        $this->isLastJobItem($jobItem);

        return $jobItem;
    }

    /**
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @return JobService
     */
    public function getJobService(): JobService
    {
        return $this->jobService;
    }

    /**
     * @return MessageBusInterface
     */
    public function getMessageBus(): MessageBusInterface
    {
        return $this->messageBus;
    }

    /**
     * @return UserService
     */
    public function getUserService(): UserService
    {
        return $this->userService;
    }

    protected function isLastJobItem($jobItem)
    {
        $cacheKey = sprintf('%s:lastItem', $this->job->getGuid()->toString());
        if ($jobItem->getGuid()->toString() === $this->cache->get($cacheKey)) {
            $this->jobService->dispatch(new JobCompleteEvent($this->job));
        }
    }

    protected function setLastJobItem(JobItem $jobItem, PersistentCollection $jobItems, bool $force = false)
    {
        if ($force || $jobItem === $jobItems->last()) {
            $cacheKey = sprintf('%s:lastItem', $this->job->getGuid()->toString());
            $this->cache->setex($cacheKey, 1500, $jobItem->getGuid()->toString());
        }
    }
}
