<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use App\Helper\ValidationHelper;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractMessageService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var ValidationHelper
     */
    protected $validator;

    /**
     * AbstractMessageService constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @param ValidationHelper         $validator
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->validator = $validator;
    }

    /**
     * @return ValidationHelper
     */
    public function getValidator(): ValidationHelper
    {
        return $this->validator;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }
}
