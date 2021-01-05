<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\DTO\SyncOrdersRequest;
use App\Entity\Job;
use App\Message\Job\JobRequestMessage;
use App\Message\SyncOrdersRequestMessage;
use App\Service\DefaultTypeService;
use App\Service\JobService;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * Class SyncOrdersDataPersister.
 */
class SyncOrdersDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var DefaultTypeService
     */
    private $defaultTypeService;

    /**
     * @var JobService
     */
    private $jobService;

    /**
     * SyncOrdersDataPersister constructor.
     *
     * @param MessageBusInterface $messageBus
     * @param DefaultTypeService  $defaultTypeService
     * @param JobService          $jobService
     */
    public function __construct(
        MessageBusInterface $messageBus,
        DefaultTypeService $defaultTypeService,
        JobService $jobService
    ) {
        $this->messageBus = $messageBus;
        $this->defaultTypeService = $defaultTypeService;
        $this->jobService = $jobService;
    }

    /**
     * @param SyncOrdersRequest $data
     * @param array             $context
     *
     * @return bool
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof SyncOrdersRequest;
    }

    /**
     * @param SyncOrdersRequest $data
     * @param array             $context
     *
     *@throws \Exception
     *
     *@return Job|object|void
     */
    public function persist($data, array $context = []): Job
    {
        $job = $data->getJob();
        $account = $data->getAccount();
        $source = $data->getSource() ?? $this->defaultTypeService->getDefaultSource();

        $jobRequest = new JobRequestMessage(
            $account->getGuid()->toString(),
            $source->getGuid()->toString(),
            new SyncOrdersRequestMessage($data)
        );

        if (null !== $job) {
            $jobRequest->setJobId($job->getGuid()->toString());
        }

        $envelope = $this->messageBus->dispatch($jobRequest);

        $handledStamp = $envelope->last(HandledStamp::class);

        $job = $handledStamp->getResult();

        $job->setConfig($data);

        return $job;
    }

    /**
     * @param       $data
     * @param array $context
     */
    public function remove($data, array $context = [])
    {
    }
}
