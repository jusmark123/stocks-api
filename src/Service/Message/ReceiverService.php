<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use App\Event\LoggerEvent;
use App\Helper\ValidationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ReceiverService.
 */
abstract class ReceiverService extends AbstractMessageService implements ReceiverServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * ReceiverService constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param EntityManagerInterface   $entityManager
     * @param LoggerInterface          $logger
     * @param ValidationHelper         $validator
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->entityManager = $entityManager;
        parent::__construct($dispatcher, $logger, $validator);
    }

    /**
     * @return [type]
     */
    public function checkConnection()
    {
        if (null !== $this->entityManager->getConnection()
            && false === $this->entityManager->getConnection()->ping()) {
            $this->entityManager->getConnection()->close();
            $this->entityManager->getConnection()->connect();
        }
    }

    /**
     * @param string $message
     */
    public function preReceive(string $message): void
    {
        $this->dispatcher->dispatch(
            new LoggerEvent($message),
            LoggerEvent::getInfoEventName());

        $this->checkConnection();
    }

    /**
     * @param string $message
     */
    public function postReceive(string $message): void
    {
        $this->dispatcher->dispatch(
            new LoggerEvent($message),
            LoggerEvent::getInfoEventName());

        $this->entityManager->getUnitOfWork()->clear();
    }
}
