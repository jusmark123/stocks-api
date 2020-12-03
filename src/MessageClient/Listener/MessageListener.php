<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Listener;

use App\MessageClient\Entity\MessagesEntityInterface;
use App\MessageClient\Service\MessagesService;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

/**
 * Class MessageListener.
 */
class MessageListener
{
    const IGNORING_COMMAND_MESSAGE = 'ignoring command: %s';
    const EXCEPTION_MESSAGE = 'Reinitializing because exception occurred: %s';
    const CONSOLE_TERMINATE_MESSAGE = 'ConsoleTerminatedEvent with exit code: %d';
    const KERNEL_TERMINATE_MESSAGE = 'TerminateEvent with response status code: %d';

    const CREATED = 'created';
    const UPDATED = 'updated';
    const DELETED = 'deleted';

    /**
     * @var MessagesService
     */
    private $messagesService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $ignoreCommands;

    /**
     * @var bool
     */
    private $enabled = true;

    /**
     * @var array
     */
    private $entities = [];

    /**
     * MessageListener constructor.
     *
     * @param MessagesService $messagesService
     * @param LoggerInterface $logger
     * @param array           $ignoreCommands
     */
    public function __construct(MessagesService $messagesService, LoggerInterface $logger, array $ignoreCommands = [])
    {
        $this->messagesService = $messagesService;
        $this->logger = $logger;
        $this->ignoreCommands = $ignoreCommands;

        $this->initialize();
    }

    public function initialize()
    {
        foreach ($this->getActions() as $action) {
            $this->entities[$action] = [];
        }
    }

    /**
     * @return string[]
     */
    public function getActions()
    {
        return [
            self::CREATED,
            self::UPDATED,
            self::DELETED,
        ];
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @param string                  $action
     * @param MessagesEntityInterface $entity
     */
    public function addEntity(string $action, MessagesEntityInterface $entity): void
    {
        if (true === $this->isEnabled()) {
            if (\in_array($action, $this->getActions(), true)) {
                $this->entities[$action][] = $entity;
            }
        }
    }

    /**
     * @return array
     */
    public function getEntities(string $action, bool $clear = false): ?array
    {
        if (\in_array($action, $this->getActions(), true)) {
            $entities = $this->entities[$action] ?? [];
            if (true === $clear) {
                $this->entities[$action] = [];
            }

            return $entities;
        }

        return null;
    }

    public function flushEntities(): void
    {
        if (true === $this->isEnabled()) {
            foreach ($this->getActions() as $action) {
                foreach ($this->getEntities($action, true) as $entity) {
                    $this->messagesService->createMessaage($action, $entity);
                }
            }
        }
    }

    private function reset(): void
    {
        $this->initialize();
        $this->messagesService->clearMessages();
    }

    private function finish(): void
    {
        if (true === $this->isEnabled()) {
            $this->messagesService->sendMessages();
        }
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();
        if (\in_array($command->getName(), $this->ignoreCommands, true)) {
            $message = sprintf(self::CONSOLE_TERMINATE_MESSAGE, $command->getName());
            $this->logger->debug($message);

            $this->setEnabled(false);
        }
    }

    /**
     * @param ConsoleErrorEvent $event
     */
    public function onConsoleException(ConsoleErrorEvent $event)
    {
        $exception = $event->getError();
        $message = sprintf(self::EXCEPTION_MESSAGE, $exception->getMessage());
        $this->logger->debug($message);

        $this->reset();
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $message = sprintf(self::EXCEPTION_MESSAGE, $exception->getMessage());
        $this->logger->debug($message);

        $this->reset();
    }

    /**
     * @param ConsoleTerminateEvent $event
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event)
    {
        $exitCode = $event->getExitCode();
        $message = sprintf(self::CONSOLE_TERMINATE_MESSAGE, $exitCode);
        $this->logger->debug($message);

        $this->finish();
    }

    /**
     * @param TerminateEvent $event
     */
    public function onKernelTerminate(TerminateEvent $event)
    {
        $statusCode = $event->getResponse()->getStatusCode();
        $message = sprintf(self::KERNEL_TERMINATE_MESSAGE, $statusCode);
        $this->logger->debug($message);

        $this->finish();
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof MessagesEntityInterface) {
            $this->addEntity(self::CREATED, $entity);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof MessagesEntityInterface) {
            $this->addEntity(self::UPDATED, $entity);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof MessagesEntityInterface) {
            $this->addEntity(self::UPDATED, $entity);
        }
    }

    /**
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        $this->flushEntities();
    }
}
