<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Job;
use App\Entity\Order;
use App\Event\AbstractJobFailedEvent;

/**
 * Class OrderInfoProcessFailedEvent.
 */
class OrderInfoProcessFailedEvent extends AbstractJobFailedEvent implements OrderInfoFailedEventInterface
{
    use OrderInfoFailedEventTrait;

    const EVENT_NAME = 'order-info.process';

    /**
     * OrderInfoProcessFailedEvent constructor.
     *
     * @param array                   $orderInfoMessage
     * @param \Exception              $exception
     * @param Job                     $job
     * @param Order|null              $order
     * @param OrderInfoInterface|null $orderInfo
     */
    public function __construct(
        array $orderInfoMessage,
        \Exception $exception,
        Job $job,
        ?Order $order = null,
        ?OrderInfoInterface $orderInfo = null
    ) {
        $this->order = $order;
        $this->orderInfo = $orderInfo;
        $this->orderInfoMessage = $orderInfoMessage;
        parent::__construct($job, $exception);
    }
}
