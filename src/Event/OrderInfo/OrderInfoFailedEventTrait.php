<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\OrderInfoInterface;
use App\Entity\JobItem;
use App\Entity\Order;

trait OrderInfoFailedEventTrait
{
    /**
     * @var Order|null
     */
    protected $order;

    /**
     * @var OrderInfoInterface|null
     */
    protected $orderInfo;

    /**
     * @var array|null
     */
    protected $orderInfoMessage;

    /**
     * OrderInfoProcessFailedEvent constructor.
     *
     * @param \Exception              $exception
     * @param JobItem|null            $jobItem
     * @param Order|null              $order
     * @param OrderInfoInterface|null $orderInfo
     */
    public function __construct(
        \Exception $exception,
        ?JobItem $jobItem,
        ?Order $order = null,
        ?OrderInfoInterface $orderInfo = null
    ) {
        $job = null;
        $this->order = $order;
        $this->orderInfo = $orderInfo;
        if (null !== $jobItem) {
            $job = $jobItem->getJob();
            $this->orderInfoMessage = $jobItem->getData();
        }
        parent::__construct($exception, $job, $jobItem);
    }

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @return OrderInfoInterface
     */
    public function getOrderInfo(): ?OrderInfoInterface
    {
        return $this->orderInfo;
    }

    /**
     * @return array
     */
    public function getOrderInfoMessage(): ?array
    {
        return $this->orderInfoMessage;
    }
}
