<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Order;

use App\DTO\Brokerage\Interfaces\OrderRequestInterface;
use App\Event\AbstractEvent;

/**
 * Class OrderReceivedEvent.
 */
class OrderReceivedEvent extends AbstractEvent
{
    const EVENT_NAME = 'order.receive';

    /**
     * @var array
     */
    private $orderRequestMessage;

    /**
     * @var OrderRequestInterface
     */
    protected $orderRequest;

    /**
     * OrderReceivedEvent Constructor.
     *
     * @param OrderRequestInterface $orderRequest
     */
    public function __construct(array $orderRequestMessage, OrderRequestInterface $orderRequest)
    {
        $this->orderRequestMessage = $orderRequestMessage;
        $this->orderRequest = $orderRequest;
    }

    /**
     * @return OrderRequestInterface
     */
    public function getOrderRequest(): OrderRequestInterface
    {
        return $this->orderRequest;
    }

    /**
     * @return array
     */
    public function getOrderRequestMessage(): array
    {
        return $this->orderRequestMessage;
    }
}
