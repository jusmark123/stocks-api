<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Event\AbstractFailedEvent;

/**
 * Class OrderInfoPublishFailedEvent.
 */
class OrderInfoPublishFailedEvent extends AbstractFailedEvent
{
    const EVENT_NAME = 'order-info.publish';

    /** @var array */
    private $orderInfoMessage;

    /**
     * @var OrderInfoInterface
     */
    private $orderInfo;

    /**
     * OrderInfoPublishFailedEvent constructor.
     *
     * @param array                   $orderInfoMessage
     * @param \Exception              $exception
     * @param OrderInfoInterface|null $orderInfo
     */
    public function __construct(array $orderInfoMessage, \Exception $exception, ?OrderInfoInterface $orderInfo = null)
    {
        $this->orderInfoMessage = $orderInfoMessage;
        $this->orderInfo = $orderInfo;
        parent::__construct($exception);
    }

    /**
     * @return array
     */
    public function getOrderInfoMessage(): array
    {
        return $this->orderInfoMessage;
    }

    /**
     * @return OrderInfoInterface
     */
    public function getOrderInfo(): OrderInfoInterface
    {
        return $this->orderInfo;
    }
}
