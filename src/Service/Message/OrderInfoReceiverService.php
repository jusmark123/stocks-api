<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use App\Constants\Transport\JobConstants;
use App\Entity\Job;
use App\Helper\ValidationHelper;
use App\Message\Factory\OrderInfoMessageFactory;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OrderInfoReceiverService.
 */
class OrderInfoReceiverService extends ReceiverService
{
    /**
     * @var OrderService
     */
    protected $orderService;

    /** @var OrderInfoMessageFactory */
    private $messageFactory;

    /**
     * OrderInfoReceiverService constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param EntityManagerInterface   $entityManager
     * @param LoggerInterface          $logger
     * @param OrderInfoMessageFactory  $messageFactory
     * @param OrderService             $orderService
     * @param ValidationHelper         $validator
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        OrderInfoMessageFactory $messageFactory,
        OrderService $orderService,
        ValidationHelper $validator
    ) {
        $this->orderService = $orderService;
        $this->messageFactory = $messageFactory;
        parent::__construct($dispatcher, $entityManager, $logger, $validator);
    }

    /**
     * @return OrderService
     */
    public function getOrderService(): OrderService
    {
        return $this->orderService;
    }

    /**
     * @param array $message
     */
    public function receive(array $message)
    {
        $this->preReceive('DB:Listener start receiving job message');

        $this->logger->debug('packet', $message);

        $orderInfo = $this->messageFactory->createFromMessage($message);

//        $job = $this->entityManager->getRepository(Job::class)->findOneBy(['guid' => $job_id]);
//        $job->setStatus(JobConstants::JOB_RECEIVED_STATUS);
    }
}
