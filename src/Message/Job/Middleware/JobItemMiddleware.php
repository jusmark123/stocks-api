<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message\Job\Middleware;

use App\Entity\Manager\JobEntityManager;
use App\Message\Job\Stamp\JobItemStamp;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

/**
 * Class JobItemMiddleware.
 */
class JobItemMiddleware implements MiddlewareInterface
{
    /**
     * @var JobService
     */
    private $jobService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Client
     */
    private $cache;

    /**
     * JobItemMiddleware constructor.
     *
     * @param Client           $cache
     * @param JobEntityManager $entityManager
     * @param JobService       $jobService
     */
    public function __construct(
        Client $cache,
        JobEntityManager $entityManager,
        JobService $jobService)
    {
        $this->cache = $cache;
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

        $stamp = $envelope->last(JobItemStamp::class);

        return $envelope;
    }
}
