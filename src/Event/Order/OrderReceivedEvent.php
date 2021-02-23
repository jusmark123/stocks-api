<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\Order;

use App\DTO\Brokerage\BrokerageOrderRequestInterface;
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
     * @var BrokerageOrderRequestInterface
     */
    protected $orderRequest;

    /**
     * OrderReceivedEvent Constructor.
     *
     * @param BrokerageOrderRequestInterface $orderRequest
     */
    public function __construct(array $orderRequestMessage, BrokerageOrderRequestInterface $orderRequest)
    {
        $this->orderRequestMessage = $orderRequestMessage;
        $this->orderRequest = $orderRequest;
    }

    /**
     * @return BrokerageOrderRequestInterface
     */
    public function getOrderRequest(): BrokerageOrderRequestInterface
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
