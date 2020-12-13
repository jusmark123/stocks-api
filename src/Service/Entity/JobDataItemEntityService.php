<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Entity;

use App\Constants\Transport\JobConstants;
use App\Entity\AbstractEntity;
use App\Entity\Job;
use App\Entity\JobDataItem;
use App\Helper\ValidationHelper;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Exception\InvalidMessage;
use App\MessageClient\Exception\PublishException;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\DefaultTypeService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class JobDataItemEntityService.
 */
class JobDataItemEntityService extends AbstractEntityService
{
    /**
     * @var ClientPublisher
     */
    private $clientPublisher;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * JobDataItemEntityService constructor.
     *
     * @param ClientPublisher          $clientPublisher
     * @param DefaultTypeService       $defaultTypeService
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param MessageFactory           $messageFactory
     * @param LoggerInterface          $logger
     * @param ValidationHelper         $validator
     */
    public function __construct(
        ClientPublisher $clientPublisher,
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        MessageFactory $messageFactory,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->clientPublisher = $clientPublisher;
        $this->dispatcher = $dispatcher;
        $this->messageFactory = $messageFactory;
        parent::__construct($defaultTypeService, $entityManager, $logger, $validator);
    }

    /**
     * @param     $data
     * @param Job $job
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return AbstractEntity
     */
    public function create($data, Job $job): JobDataItem
    {
    }

    /**
     * @param JobDataItem $jobDataItem
     * @param \Exception  $exception
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return JobDataItem
     */
    public function setError(JobDataItem $jobDataItem, \Exception $exception)
    {
        $jobDataItem
            ->setErrorMessage($exception->getMessage())
            ->setErrorTrace($exception->getTraceAsString());
        $this->save($jobDataItem);

        return $jobDataItem;
    }

    /**
     * @param JobDataItem $jobDataItem
     * @param string      $status
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @return JobDataItem
     */
    public function setStatus(JobDataItem $jobDataItem, string $status)
    {
        $jobDataItem->setStatus($status);
        $this->save($jobDataItem);

        return $jobDataItem;
    }

    /**
     * @param JobDataItem $jobDataItem
     * @param array       $headers
     * @param string      $topic
     *
     * @throws PublishException
     * @throws InvalidMessage
     */
    public function publishJobItem(JobDataItem $jobDataItem, array $headers, string $topic)
    {
        $headers = array_merge($headers, [
            JobConstants::JOB_ID_HEADER_NAME => $jobDataItem->getJob()->getGuid()->toString(),
        ]);

        $packet = $this->messageFactory->createPacket(
            $topic,
            json_encode($jobDataItem->getData()),
            $headers
        );
        $this->clientPublisher->publish($packet);
    }
}
