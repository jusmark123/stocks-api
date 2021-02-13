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
use App\Event\Job\JobCompleteEvent;
use App\Message\Job\JobRequestMessageInterface;
use App\Service\Entity\JobEntityService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class JobService extends AbstractService
{
    /**
     * @var DefaultTypeService
     */
    private $defaultTypeService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

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
     * @var Client
     */
    private $cache;

    /**
     * JobService constructor.
     *
     * @param Client                   $cache
     * @param DefaultTypeService       $defaultTypeService
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param JobEntityService         $jobEntityService
     * @param JobEntityManager         $jobEntityManager
     * @param LoggerInterface          $logger
     */
    public function __construct(
        Client $cache,
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        JobEntityService $jobEntityService,
        JobEntityManager $jobEntityManager,
        LoggerInterface $logger
    ) {
        parent::__construct($logger);
        $this->cache = $cache;
        $this->defaultTypeService = $defaultTypeService;
        $this->dispatcher = $dispatcher;
        $this->entityManager = $entityManager;
        $this->jobEntityService = $jobEntityService;
        $this->jobEntityManager = $jobEntityManager;
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
     * @param JobRequestMessageInterface|null $request
     * @param User|null                       $user
     * @param Account|null                    $account
     * @param Source|null                     $source
     * @param mixed                           $jobData
     *
     * @return Job
     */
    public function createJob(
        JobRequestMessageInterface $request,
        ?User $user = null,
        ?Account $account = null,
        ?Source $source = null,
        $jobData = null
    ) {
        $user = $user ?? $this->defaultTypeService->getDefaultUser();
        $job = JobFactory::create()
            ->setAccount($account)
            ->setRequestHash(md5(serialize($request->getRequest())))
            ->setName($request->getJobName())
            ->setDescription($request->getJobDescription())
            ->setStatus(JobConstants::JOB_CREATED)
            ->setSource($source ?? $this->defaultTypeService->getDefaultSource())
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

        $this->jobEntityService->validate($job);

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
     * @param $job
     *
     * @return int
     */
    public function getCachedJobItemCount($job)
    {
        return $this->cache->hlen(sprintf('job:%s:job_items:*', $job->getGuid()->toString()));
    }

    public function getJobItemCount(Job $job)
    {
        return $this->entityManager
            ->getRepository(JobItem::class)
            ->count(['job' => $job]);
    }

    /**
     * @param JobRequestMessageInterface $request
     * @param array                      $statuses
     *
     * @return mixed
     */
    public function getJobs(JobRequestMessageInterface $request, array $statuses)
    {
        return $this->jobEntityManager->findBy([
            'requestHash' => md5(serialize($request->getRequest())),
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
            return null !== $jobItem->getFailedAt();
        });

        return $items->count() > 0;
    }

    /**
     * @param Job $job
     */
    public function isJobComplete(Job $job)
    {
        $items = $job->getJobItems()->filter(function (JobItem $jobItem) {
            return null === $jobItem->getProcessedAt();
        });

        if (0 === $items->count()) {
            $this->dispatch(new JobCompleteEvent($job));
        }
    }

    /**
     * @param Job $job
     */
    public function save(Job $job)
    {
        $this->jobEntityService->save($job);
    }

    /**
     * @param JobItem     $jobItem
     * @param Job         $job
     * @param string|null $id
     *
     * @return mixed
     */
    public function cacheJobItem(JobItem $jobItem, Job $job, ?string $id = null)
    {
        if (null !== $id) {
            $jobItem->setGuid(Uuid::fromString($id));
        }
        $cacheKey = sprintf('job:%s:job_items', $job->getGuid()->toString());
        $this->cache->hset($cacheKey, $jobItem->getGuid()->toString(), serialize($jobItem));

        return $jobItem;
    }

    /**
     * @param      $data
     * @param Job  $job
     * @param null $id
     *
     * @return JobItem
     */
    public function createJobItem($data, Job $job, ?string $uniqueKey = null): JobItem
    {
        $jobItem = JobItemFactory::create()
            ->setData($data)
            ->setStatus(JobConstants::JOB_QUEUED)
            ->setJob($job);

        if (null !== $uniqueKey) {
            $cacheKey = sprintf(
                'job:%s:uniqueKey:%s',
                $job->getGuid()->toString(),
                $uniqueKey
            );
            $this->cache->setex($cacheKey, 1500, $jobItem->getGuid()->toString());
        }

        $job->addJobItem($jobItem);

        return $jobItem;
    }

    /**
     * @param Job    $job
     * @param string $id
     *
     * @return mixed|string|null
     */
    public function getCachedJobItem(Job $job, string $id)
    {
        $cacheKey = sprintf('job:%s:job_items', $job->getGuid()->toString());
        $jobItem = $this->cache->hget($cacheKey, $id);

        if (null !== $jobItem) {
            $jobItem = unserialize($jobItem, true);
        }

        return $jobItem;
    }

    /**
     * @param Job $job
     *
     * @return array
     */
    public function getCachedJobItems(Job $job)
    {
        yield $this->cache->hscan(sprintf('job:%s:job_items', $job->getGuid()->toString()), 0);
    }

    public function getJobItemUniqueKey(JobItem $jobItem)
    {
        $cacheKey = sprintf(
            'job:%s:uniqueKey:%s',
            $job->getGuid()->toString(),
            $uniqueKey
        );
    }

    /**
     * @param $job
     * @param $jobItem
     * @param $uniqueKey
     *
     * @return int
     */
    public function jobItemExists($job, $uniqueKey): bool
    {
        $cacheKey = sprintf(
            'job:%s:uniqueKey:%s',
            $job->getGuid()->toString(),
            $uniqueKey
        );

        return (bool) $this->cache->exists($cacheKey);
    }
}
