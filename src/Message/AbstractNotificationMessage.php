<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message;

/**
 * Class AbstractNotificationMessage.
 */
class AbstractNotificationMessage
{
    protected const TOPIC_ARN = '';

    /**
     * @var array
     */
    protected $message;

    /**
     * AbstractNotificationMessage constructor.
     *
     * @param array $message
     */
    public function __construct(array $message)
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getTopicArn(): string
    {
        return self::TOPIC_ARN;
    }
}
