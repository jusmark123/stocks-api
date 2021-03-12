<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\DTO\SyncTickersRequest;
use App\Entity\Job;
use App\Message\Job\JobRequestMessage;
use App\Message\SyncTickersRequestMessage;
use App\Service\DefaultTypeService;
use App\Service\JobService;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * Class SyncTickersDataPersister.
 */
class SyncTickersDataPersister implements DataPersisterInterface
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
     * SyncTickersDataPersister constructor.
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
     * @param $data
     *
     * @return bool
     */
    public function supports($data): bool
    {
        return $data instanceof SyncTickersRequest;
    }

    /**
     * @param $data
     *
     * @return Job
     */
    public function persist($data): Job
    {
        $job = $data->getJob();
        $account = $data->getAccount();
        $source = $data->getSource() ?? $this->defaultTypeService->getDefaultSource();

        $jobRequest = new JobRequestMessage(
            $account->getGuid()->toString(),
            $source->getGuid()->toString(),
            new SyncTickersRequestMessage($data)
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
     * @param $data
     */
    public function remove($data)
    {
    }
}
