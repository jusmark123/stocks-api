<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Message;

class ApplicationMessage extends AbstractNotificationMessage
{
    /**
     * @var string
     */
    private $topicArn;

    /**
     * ApplicationMessage constructor.
     *
     * @param array  $message
     * @param string $topicArn
     */
    public function __construct(array $message, string $topicArn)
    {
        $this->topicArn = $topicArn;
        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getTopicArn(): string
    {
        return $this->topicArn;
    }
}
