<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job\Middleware;

use App\Entity\Manager\JobEntityManager;
use App\Event\Job\JobInProgressEvent;
use App\Event\Job\JobPublishedEvent;
use App\Message\Job\Stamp\JobStamp;
use App\Service\JobService;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;

class JobMiddleware implements MiddlewareInterface
{
    /**
     * @var JobService
     */
    private $jobService;

    /**
     * @var JobEntityManager
     */
    private $entityManager;

    /**
     * JobMiddleware constructor.
     *
     * @param JobEntityManager $entityManager
     * @param JobService       $jobService
     */
    public function __construct(JobEntityManager $entityManager, JobService $jobService)
    {
        $this->entityManager = $entityManager;
        $this->jobService = $jobService;
    }

    /**
     * @param Envelope       $envelope
     * @param StackInterface $stack
     *
     * @return Envelope
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);

//        $stamp = $envelope->last(JobStamp::class);
//
//        if (null !== $stamp) {
//            $job = $this->entityManager->findOneby(['guid' => $stamp->getJobId()]);
//
//            if ($envelope->last(ReceivedStamp::class)) {
//                $this->jobService->dispatch(new JobInProgressEvent($job));
//            } elseif ($envelope->last(SentStamp::class)) {
//                $this->jobService->dispatch(new JobPublishedEvent($job));
//            }
//        }

        return $envelope;
    }
}
