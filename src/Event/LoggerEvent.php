<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event;

/**
 * Class LoggerEvent.
 */
class LoggerEvent extends AbstractEvent
{
    const LEVEL_ERROR = 'ERROR';
    const LEVEL_INFO = 'INFO';
    const LEVEL_DEBUG = 'DEBUG';

    const EVENT_NAME = 'logger';

    /**
     * LoggerEvent Constructor.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return LoggerEvent
     */
    public function setMessage(string $message): LoggerEvent
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public static function getErrorEventName(): string
    {
        return parent::getEventName().self::LEVEL_ERROR;
    }

    /**
     * @return string
     */
    public static function getInfoEventName(): string
    {
        return parent::getEventName().self::LEVEL_INFO;
    }

    /**
     * @return string
     */
    public static function getDebugEventName(): string
    {
        return parent::getEventName().self::LEVEL_DEBUG;
    }
}
