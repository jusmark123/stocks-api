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
use App\Event\Job\JobCancelledEvent;
use App\Event\Job\JobProcessFailedEvent;
use App\Event\Job\JobReceiveFailedEvent;
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
        $jobId = $jobRequestMessage->getJobId();
        $requestMessage = $jobRequestMessage->getRequestMessage();

        /** @var Job|null $job */
        $job = $this->entityManager
            ->getRepository(Job::class)
            ->findOneBy(['guid' => $jobId]);

        try {
            if (null === $job) {
                /** @var Account $account */
                $account = $this->entityManager
                    ->getRepository(Account::class)
                    ->findOneBy(['guid' => $jobRequestMessage->getAccountId()]);

                if (!$account instanceof Account) {
                    throw new ItemNotFoundException('Account not found');
                }

                /** @var Source $source */
                $source = $this->entityManager
                    ->getRepository(Source::class)
                    ->findOneBy(['guid' => $jobRequestMessage->getSourceId()]);

                if (!$source instanceof Source) {
                    throw new ItemNotFoundException('Source not found');
                }

                $job = $this->entityManager
                    ->getRepository(Job::class)
                    ->findOneBy([
                        'name' => $requestMessage->getJobName(),
                        'account' => $account,
                        'source' => $source,
                        'status' => [
                            JobConstants::JOB_IN_PROGRESS,
                            JobConstants::JOB_PROCESSED,
                        ],
                    ]);

                if (!$job instanceof Job) {
                    $job = $this->jobService->createJob(
                        $requestMessage->getJobName(),
                        $requestMessage->getJobDescription(),
                        $jobRequestMessage->getRequestMessage(),
                        null,
                        $this->userService->getCurrentUser(),
                        $account,
                        $source
                    );

                    $this->jobService->save($job);
                } else {
                    $this->jobService->dispatch(new JobCancelledEvent($job));

                    return $job->setStatus(JobConstants::JOB_CANCELLED);
                }
            }

            if (JobConstants::JOB_COMPLETE === $job->getStatus()) {
                throw new JobCompletedException($job);
            }

            if (!\in_array($job->getStatus(), [JobConstants::JOB_PROCESSED, JobConstants::JOB_IN_PROGRESS], true)) {
                $job->setStatus(JobConstants::JOB_PENDING);
                $this->jobService->save($job);
            }

            $requestMessage->setJobId($job->getGuid()->toString());

            try {
                $envelope = (new Envelope($requestMessage))->with(new JobStamp($job->getGuid()->toString()));
                $this->getMessageBus()->dispatch($envelope);
            } catch (\Exception $e) {
                $this->jobService->dispatch(new JobProcessFailedEvent($e, $job, null));
            }
        } catch (JobCompletedException $e) {
            $job->setErrorMessage($e->getMessage());

            return $job;
        } catch (\Exception $e) {
            $this->jobService->dispatch(new JobReceiveFailedEvent($e, $job, null));
        }

        return $job;
    }
}
