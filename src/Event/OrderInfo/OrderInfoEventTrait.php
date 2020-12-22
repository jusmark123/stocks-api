<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Event\OrderInfo;

use App\DTO\Brokerage\OrderInfoInterface;
use App\Entity\Job;
use App\Entity\JobItem;
use App\Entity\Order;

trait OrderInfoEventTrait
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * @var OrderInfoInterface
     */
    protected $orderInfo;

    /**
     * @var array
     */
    protected $orderInfoMessage;

    /**
     * OrderInfoReceivedEvent constructor.
     *
     * @param Job                     $job
     * @param JobItem                 $jobItem
     * @param OrderInfoInterface|null $orderInfo
     * @param Order|null              $order
     */
    public function __construct(
        Job $job,
        JobItem $jobItem,
        ?OrderInfoInterface $orderInfo = null,
        ?Order $order = null
    ) {
        $this->order = $order;
        $this->orderInfo = $orderInfo;
        $this->orderInfoMessage = $jobItem->getData();
        parent::__construct($job, $jobItem);
    }

    /**
     * @return Order
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
    public function getOrderInfoMessage(): array
    {
        return $this->orderInfoMessage;
    }
}
