<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Constants\Transport\JobConstants;
use App\Constants\Transport\Queue;
use App\Entity\Account;
use App\Entity\Factory\JobDataItemFactory;
use App\Entity\Factory\JobFactory;
use App\Entity\Job;
use App\Entity\JobDataItem;
use App\Entity\Source;
use App\Entity\User;
use App\Event\AbstractEvent;
use App\Event\Job\JobCreatedEvent;
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
     * JobService constructor.
     *
     * @param DefaultTypeService       $defaultTypeService
     * @param EventDispatcherInterface $dispatcher
     * @param JobEntityService         $jobEntityService
     * @param LoggerInterface          $logger
     */
    public function __construct(
        DefaultTypeService $defaultTypeService,
        EventDispatcherInterface $dispatcher,
        JobEntityService $jobEntityService,
        LoggerInterface $logger
    ) {
        $this->defaultTypeService = $defaultTypeService;
        $this->dispatcher = $dispatcher;
        $this->jobEntityService = $jobEntityService;
        parent::__construct($logger);
    }

    /**
     * @param string       $jobName
     * @param string       $jobDescription
     * @param mixed        $jobData
     * @param User|null    $user
     * @param Account|null $account
     * @param Source|null  $source
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return Job
     */
    public function createJob(
        string $jobName,
        string $jobDescription,
        $jobData,
        ?User $user = null,
        ?Account $account = null,
        ?Source $source = null
    ) {
        $source = $source ?? $this->defaultTypeService->getDefaultSource();
        $user = $user ?? $this->defaultTypeService->getDefaultUser();

        $job = JobFactory::create()
            ->setAccount($account)
            ->setName($jobName)
            ->setDescription($jobDescription)
            ->setStatus(JobConstants::JOB_CREATED)
            ->setSource($source)
            ->setUser($user)
            ->setCreatedBy($user->getGuid()->toString())
            ->setModifiedBy($user->getGuid()->toString());

        if (\is_array($jobData)) {
            foreach ($jobData as $jobDataItem) {
                $this->setJobDataItem($jobDataItem, $job);
            }
        } else {
            $this->setJobDataItem($jobData, $job);
        }

        $this->jobEntityService->save($job);

        $this->dispatch(new JobCreatedEvent($job));

        return $job;
    }

    /**
     * @param     $data
     * @param Job $job
     *
     * @return JobDataItem
     */
    public function setJobDataItem($data, Job $job): JobDataItem
    {
        $jobItemData = JobDataItemFactory::create()
            ->setData($data)
            ->setStatus(JobConstants::JOB_PENDING)
            ->setJob($job);

        $job->addJobData($jobItemData);

        return $jobItemData;
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
            $headers,
        );
    }

    /**
     * @return JobEntityService
     */
    public function getJobEntityService()
    {
        return $this->jobEntityService;
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
}
