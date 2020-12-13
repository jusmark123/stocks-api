<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Job;
use App\Event\AbstractJobFailedEvent;

/**
 * Class OrderInfoPublishFailedEvent.
 */
class OrderInfoPublishFailedEvent extends AbstractJobFailedEvent implements OrderInfoFailedEventInterface
{
    use OrderInfoFailedEventTrait;

    const EVENT_NAME = 'order-info.publish';

    /**
     * OrderInfoPublishFailedEvent constructor.
     *
     * @param array                   $orderInfoMessage
     * @param \Exception              $exception
     * @param Job                     $job
     * @param OrderInfoInterface|null $orderInfo
     */
    public function __construct(
        array $orderInfoMessage,
        \Exception $exception,
        Job $job,
        ?OrderInfoInterface $orderInfo = null
    ) {
        $this->orderInfoMessage = $orderInfoMessage;
        $this->orderInfo = $orderInfo;
        parent::__construct($job, $exception);
    }
}
