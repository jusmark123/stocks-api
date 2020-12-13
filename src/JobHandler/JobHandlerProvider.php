<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\JobHandler;

use App\Entity\Job;
use App\Exception\JobConfigurationException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

class JobHandlerProvider implements JobHandlerProviderInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var iterable
     */
    private $jobHandlers;

    public function __construct(
        LoggerInterface $logger,
        iterable $jobHandlers
    ) {
        $this->setLogger($logger);
        if ($jobHandlers instanceof \Traversable) {
            $jobHandlers = iterator_to_array($jobHandlers);
        }
        $this->setJobHandlers($jobHandlers);
    }

    /**
     * @return array
     */
    public function getJobs()
    {
        return $this->jobHandlers;
    }

    /**
     * @param array $jobHandlers
     *
     * @throws JobConfigurationException
     *
     * @return $this
     */
    public function setJobHandlers(array $jobHandlers): JobHandlerProviderInterface
    {
        $this->jobHandlers = [];

        foreach ($jobHandlers as $jobHandler) {
            if (!$jobHandler instanceof JobHandlerInterface) {
                throw new JobConfigurationException(
                    'jobHandlers must implement('.JobHandlerInterface::class.')');
            }

            $this->jobHandlers = $jobHandlers;
        }

        return $this;
    }

    /**
     * @param Job         $job
     * @param string|null $resourceClass
     *
     * @return JobHandlerInterface
     */
    public function getJobHandler(Job $job, ?string $resourceClass = null): JobHandlerInterface
    {
        foreach ($this->jobHandlers as $jobInterface) {
            if ($jobInterface->supports($job->getName(), $resourceClass)) {
                return $jobInterface;
            }
        }
    }
}
