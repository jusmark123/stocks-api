<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event;

abstract class AbstractFailedEvent extends AbstractEvent
{
    const EVENT_SUFFIX = '.failed';

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * AbstractFailedEvent Constructor.
     *
     * @param \Exception $exception
     */
    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return \Exception
     */
    public function getException(): \Exception
    {
        return $this->exception;
    }

    /**
     * @return string
     */
    public static function getEventName(): string
    {
        return parent::getEventName().self::EVENT_SUFFIX;
    }
}
