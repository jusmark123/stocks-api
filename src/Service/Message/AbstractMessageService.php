<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use App\Entity\AbstractEntity;
use App\Entity\Job;
use App\Entity\User;
use App\Event\AbstractEvent;
use App\Event\LoggerEvent;
use App\Helper\ValidationHelper;
use App\MessageClient\ClientPublisher\ClientPublisher;
use App\MessageClient\Protocol\MessageFactory;
use App\Service\AbstractService;
use App\Service\DefaultTypeService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class AbstractMessageService.
 */
abstract class AbstractMessageService extends AbstractService implements MessageServiceInterface
{
    /**
     * @var DefaultTypeService
     */
    protected $defaultTypeService;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var MessageFactory
     */
    protected $messageFactory;

    /**
     * @var ClientPublisher
     */
    protected $publisher;

    /**
     * @var ValidationHelper
     */
    protected $validator;

    /**
     * AbstractMessageService constructor.
     *
     * @param DefaultTypeService       $defaultTypeService
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @param ValidationHelper         $validator
     */
    public function __construct(
        DefaultTypeService $defaultTypeService,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger,
        MessageFactory $messageFactory,
        ClientPublisher $publisher,
        ValidationHelper $validator
    ) {
        $this->defaultTypeService = $defaultTypeService;
        $this->dispatcher = $dispatcher;
        $this->entityManager = $entityManager;
        $this->messageFactory = $messageFactory;
        $this->publisher = $publisher;
        $this->validator = $validator;
        parent::__construct($logger);
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }

    /**
     * @param AbstractEntity $entity
     * @param array          $message
     *
     * @return AbstractEntity
     */
    public function buildEntityFromMessage(AbstractEntity $entity, array $message, ?User $user = null): AbstractEntity
    {
        $user = $user ?? $this->defaultTypeService->getDefaultUser();

        foreach ($message as $key => $value) {
            $method = 'set'.ucwords($key);
            if (method_exists($entity, $method)) {
                $entity->{$method}($value);
            }
        }

        $entity->setCreatedBy($user->getUsername())
            ->setModifiedBy($user->getUsername());

        return $entity;
    }

    public function checkConnection(): void
    {
        if (!$this->entityManager->isOpen()) {
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

    /**
     * @param AbstractEvent $event
     */
    public function dispatch(AbstractEvent $event)
    {
        $this->dispatcher->dispatch(
            $event,
            $event::getEventName()
        );
    }

    /**
     * @param Job        $job
     * @param \Exception $e
     */
    public function logError(Job $job, \Exception $e)
    {
        $this->logger->error($e->getMessage(), [
            'exception' => $e,
            'jobId' => $job->getGuid()->toString(),
            'jobName' => $job->getName(),
        ]);
    }

    /**
     * @param array $message
     */
    public function processed(array $message)
    {
    }

    /**
     * @param $packet
     *
     * @throws \App\MessageClient\Exception\PublishException
     */
    public function publish($packet)
    {
        $this->publisher->publish($packet);
    }
}
