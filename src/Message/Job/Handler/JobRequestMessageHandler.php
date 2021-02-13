<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job\Handler;

use ApiPlatform\Core\Exception\ItemNotFoundException;
use App\Constants\Transport\JobConstants;
use App\Entity\Account;
use App\Entity\Job;
use App\Entity\Source;
use App\Event\Job\JobInitiatedEvent;
use App\Event\Job\JobInitiateFailedEvent;
use App\Exception\JobCompletedException;
use App\Message\Job\JobRequestMessage;
use App\Message\Job\Stamp\JobStamp;
use Symfony\Component\Messenger\Envelope;

/**
 * Class JobRequestMessageHandler.
 */
class JobRequestMessageHandler extends AbstractJobMessageHandler
{
    /**
     * @param JobRequestMessage $jobRequestMessage
     *
     * @return Job|null
     */
    public function __invoke(JobRequestMessage $jobRequestMessage): ?Job
    {
        $job = null;
        $requestMessage = $jobRequestMessage->getRequestMessage();

        try {
            if (null !== $jobRequestMessage->getJobId()) {
                $job = $this->findJob($jobRequestMessage->getJobId());
            } else {
                /** @var Account $account */
                $account = $this->getEntity($jobRequestMessage->getAccountId(), Account::class);

                /** @var Source $source */
                $source = $this->getEntity($jobRequestMessage->getSourceId(), Source::class);

                // Check for existing jobs
                $jobs = $this->jobService->getJobs($requestMessage, [
                    JobConstants::JOB_INITIATED,
                    JobConstants::JOB_PENDING,
                    JobConstants::JOB_RECEIVED,
                ]);

                if (!empty($jobs)) {
                    foreach ($jobs as $dubJob) {
                        $job = $this->syncJobStatus($dubJob);
                    }

                    if (!$this->shouldProcessJob($job)) {
                        return $job;
                    }
                } else {
                    // Create new job
                    $job = $this->jobService->createJob(
                        $requestMessage,
                        $this->userService->getCurrentUser(),
                        $account,
                        $source
                    );
                }
            }

            $requestMessage->setJobId($job->getGuid()->toString());
            $envelope = (new Envelope($requestMessage))->with(new JobStamp($job->getGuid()->toString()));
            $this->getMessageBus()->dispatch($envelope);
            $this->jobService->dispatch(new JobInitiatedEvent($job));
        } catch (JobCompletedException $e) {
            $e->getJob()->setErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->jobService->dispatch(new JobInitiateFailedEvent($e, $job, null));
        }

        return $job;
    }

    /**
     * @param string $jobId
     *
     * @return Job
     */
    public function findJob(string $jobId)
    {
        $job = $this->entityManager->getRepository(Job::class)
            ->findOneBy(['guid' => $jobId]);

        if (!$job instanceof Job) {
            throw new ItemNotFoundException('Specified job not found');
        }

        return $job;
    }

    /**
     * @param Job $job
     *
     * @return bool
     */
    private function shouldProcess(Job $job): bool
    {
        return null !== $job->getFailedAt();
    }

    /**
     * @param Job $job
     *
     * @throws JobCompletedException
     *
     * @return Job
     */
    public function syncJobStatus(Job $job)
    {
        if (null !== $job->getFailedAt() && JobConstants::JOB_FAILED !== $job->getStatus()) {
            $job->setStatus(JobConstants::JOB_FAILED);
        } elseif (null !== $job->getCompletedAt() && JobConstants::JOB_COMPLETE !== $job->getStatus()) {
            $job->setStatus(JobConstants::JOB_COMPLETE);
            throw new JobCompletedException($job);
        } elseif (null !== $job->getProcessedAt() && JobConstants::JOB_PROCESSED !== $job->getStatus()) {
            $job->setStatus(JobConstants::JOB_PROCESSED);
        } elseif (null !== $job->getStartedAt() && JobConstants::JOB_IN_PROGRESS !== $job->getStatus()) {
            $job->setStatus(JobConstants::JOB_IN_PROGRESS);
        } elseif (null !== $job->getReceivedAt() && JobConstants::JOB_RECEIVED !== $job->getStatus()) {
            $job->setStatus(JobConstants::JOB_RECEIVED);
        }

        $this->jobService->save($job);

        return $job;
    }
}
