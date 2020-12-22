<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Constants\Transport\JobConstants;
use App\Constants\Transport\Queue;
use App\Entity\Account;
use App\Entity\Factory\JobFactory;
use App\Entity\Factory\JobItemFactory;
use App\Entity\Job;
use App\Entity\JobItem;
use App\Entity\Manager\JobEntityManager;
use App\Entity\Source;
use App\Entity\User;
use App\Event\AbstractEvent;
use App\Event\Job\JobCreatedEvent;
use App\Event\Job\JobCreateFailedEvent;
use App\Event\Job\JobPublishedEvent;
use App\Event\Job\JobPublishFailedEvent;
use App\Event\JobItem\JobItemQueuedEvent;
use App\Event\OrderInfo\OrderInfoPublishFailedEvent;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Exception\InvalidMessage;
use App\MessageClient\Exception\PublishException;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\Entity\JobEntityService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class JobService extends AbstractService
{
    /**
     * @var DefaultTypeService
     */
    private $defaultTypeService;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var JobEntityService
     */
    private $jobEntityService;

    /**
     * @var JobEntityManager
     */
    private $jobEntityManager;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var ClientPublisher
     */
    private $publisher;

    /**
     * JobService constructor.
     *
     * @param DefaultTypeService       $defaultTypeService
     * @param EventDispatcherInterface $dispatcher
     * @param JobEntityService         $jobEntityService
     * @param JobEntityManager         $jobEntityManager
     * @param LoggerInterface          $logger
     * @param MessageFactory           $messageFactory
     * @param ClientPublisher          $publisher
     */
    public function __construct(
        DefaultTypeService $defaultTypeService,
        EventDispatcherInterface $dispatcher,
        JobEntityService $jobEntityService,
        JobEntityManager $jobEntityManager,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        ClientPublisher $publisher
    ) {
        $this->defaultTypeService = $defaultTypeService;
        $this->dispatcher = $dispatcher;
        $this->jobEntityService = $jobEntityService;
        $this->jobEntityManager = $jobEntityManager;
        $this->messageFactory = $messageFactory;
        $this->publisher = $publisher;
        parent::__construct($logger);
    }

    /**
     * @param Job $job
     *
     * @return float
     */
    public function calculateJobPercentageComplete(Job $job): float
    {
        $items = $job->getJobItems()->filter(function ($jobItem) {
            if (JobConstants::JOB_PENDING !== $jobItem->getStatus()) {
                return true;
            }

            return false;
        });

        if (0 === $items->count()) {
            $percentComplete = 0.00;
        } else {
            $percentComplete = ($items->count() / $job->getJobItems()->count()) / 100;
        }

        return $percentComplete;
    }

    /**
     * @param string       $jobName
     * @param string       $jobDescription
     * @param array        $config
     * @param mixed        $jobData
     * @param User|null    $user
     * @param Account|null $account
     * @param Source|null  $source
     *
     * @return Job
     */
    public function createJob(
        string $jobName,
        string $jobDescription,
        $config = [],
        $jobData = null,
        ?User $user = null,
        ?Account $account = null,
        ?Source $source = null
    ) {
        try {
            $source = $source ?? $this->defaultTypeService->getDefaultSource();
            $user = $user ?? $this->defaultTypeService->getDefaultUser();

            $job = JobFactory::create()
                ->setAccount($account)
                ->setConfig($config)
                ->setName($jobName)
                ->setDescription($jobDescription)
                ->setStatus(JobConstants::JOB_CREATED)
                ->setSource($source)
                ->setUser($user)
                ->setPercentComplete(0.00)
                ->setCreatedBy($user->getGuid()->toString())
                ->setModifiedBy($user->getGuid()->toString());

            if (null !== $jobData) {
                if (\is_array($jobData)) {
                    foreach ($jobData as $jobItem) {
                        $this->createJobItem($jobItem, $job);
                    }
                } else {
                    $this->createJobItem($jobData, $job);
                }
            }

            $this->dispatch(new JobCreatedEvent($job));

            $this->publishJob(
                $job,
                Queue::REQUEST_HEADERS,
                JobConstants::JOB_REQUEST_ROUTING_KEY
            );
        } catch (\Exception $e) {
            $this->dispatch(new JobCreateFailedEvent($e, $job));
            throw $e;
        }

        return $job;
    }

    /**
     * @param AbstractEvent $event
     */
    public function dispatch(AbstractEvent $event)
    {
        $this->dispatcher->dispatch($event, $event::getEventName());
    }

    /**
     * @param array $headers
     *
     * @return array
     */
    public function getHeaders(array $headers = [])
    {
        return array_merge(
            Queue::REQUEST_HEADERS,
            $headers
        );
    }

    /**
     * @param        $account
     * @param string $jobName
     * @param array  $statuses
     *
     * @return Job|null
     */
    public function getJob($account, string $jobName, array $statuses): ?Job
    {
        return $this->jobEntityManager
            ->findOneBy([
                'account' => $account,
                'name' => $jobName,
                'status' => $statuses,
            ]);
    }

    /**
     * @return JobEntityService
     */
    public function getJobEntityService()
    {
        return $this->jobEntityService;
    }

    /**
     * @param $job
     *
     * @return bool
     */
    public function hasFailedJobs($job): bool
    {
        $items = $job->getJobItems()->filter(function ($jobItem) {
            return JobConstants::JOB_FAILED === $jobItem->getStatus();
        });

        return 0 === $items->count();
    }

    /**
     * @param $job
     *
     * @return bool
     */
    public function isComplete($job): bool
    {
        $items = $job->getJobItems()->filter(function ($jobItem) {
            return JobConstants::JOB_PENDING === $jobItem->getStatus();
        });

        return 0 === $items->count();
    }

    /**
     * @param $topic
     * @param $message
     * @param $headers
     *
     * @throws InvalidMessage
     * @throws PublishException
     */
    private function publish($topic, $message, $headers)
    {
        $packet = $this->messageFactory->createPacket($topic, $message, $headers);
        $this->publisher->publish($packet);
    }

    /**
     * @param Job    $job
     * @param array  $headers
     * @param string $topic
     *
     * @throws InvalidMessage
     * @throws PublishException
     */
    public function publishJob(Job $job, array $headers, string $topic)
    {
        try {
            $this->jobEntityService->save($job);
            $message = json_encode(['jobUUID' => $job->getGuid()->toString(), 'config' => $job->getConfig()]);
            $packet = $this->messageFactory->createPacket($topic, $message, $headers);
            $this->publisher->publish($packet);
            $this->dispatcher->dispatch(new JobPublishedEvent($job), JobPublishedEvent::getEventName());
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(new JobPublishFailedEvent($e, $job), JobPublishFailedEvent::getEventName());
            throw $e;
        }
    }

    /**
     * @param JobItem $jobItem
     * @param array   $headers
     * @param string  $topic
     *
     * @throws InvalidMessage
     * @throws PublishException
     */
    public function publishJobItem(JobItem $jobItem, array $headers, string $topic)
    {
        try {
            $headers = array_merge($headers, [
                JobConstants::JOB_ID_HEADER_NAME => $jobItem->getJob()->getGuid()->toString(),
            ]);
            $this->publish(
                $topic,
                json_encode([
                    'jobItemUUID' => $jobItem->getGuid()->toString(),
                    'jobUUID' => $jobItem->getJob()->getGuid()->toString(),
                ]),
                $headers
            );
            $this->dispatcher->dispatch(
                new JobItemQueuedEvent($jobItem),
                JobItemQueuedEvent::getEventName()
            );
        } catch (\Exception $e) {
            $this->dispatcher->dispatch(
                new OrderInfoPublishFailedEvent($e, $jobItem),
                OrderInfoPublishFailedEvent::getEventName()
            );
        }
    }

    /**
     * @param Job $job
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Job $job)
    {
        $this->jobEntityService->save($job);
    }

    /**
     * @param     $data
     * @param Job $job
     *
     * @return JobItem
     */
    public function createJobItem($data, Job $job): JobItem
    {
        $jobItem = JobItemFactory::create()
            ->setData($data)
            ->setStatus(JobConstants::JOB_PENDING)
            ->setJob($job);

        $job->addJobItem($jobItem);

        return $jobItem;
    }
}
