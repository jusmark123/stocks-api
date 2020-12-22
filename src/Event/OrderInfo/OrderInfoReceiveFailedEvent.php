<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Job;
use App\Event\AbstractFailedEvent;

/**
 * Class OrderInfoReceiveFailedEvent.
 */
class OrderInfoReceiveFailedEvent extends AbstractFailedEvent implements OrderInfoFailedEventInterface
{
    use OrderInfoFailedEventTrait;

    const EVENT_NAME = 'order-id.failed';

    /**
     * OrderInfoReceiveFailedEvent constructor.
     *
     * @param array                   $orderInfoMessage
     * @param \Exception              $exception
     * @param Job|null                $job
     * @param OrderInfoInterface|null $orderInfo
     */
    public function __construct(
        array $orderInfoMessage,
        \Exception $exception,
        ?Job $job,
        ?OrderInfoInterface $orderInfo = null
    ) {
        $this->job = $job;
        $this->orderInfoMessage = $orderInfoMessage;
        $this->orderInfo = $orderInfo;
        parent::__construct($exception);
    }
}
