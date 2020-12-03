<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Message;

use App\DTO\Brokerage\Interfaces\OrderInfoInterface;
use App\Entity\Job;
use App\Entity\Order;
use App\Event\OrderInfo\OrderInfoProcessedEvent;
use App\Event\OrderInfo\OrderInfoProcessFailedEvent;
use App\Helper\ValidationHelper;
use App\Service\AccountService;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OrderInfoProcessorService extends AbstractMessageService
{
    /** @var AccountService */
    protected $accountService;

    /** @var OrderService */
    protected $orderService;

    /**
     * OrderInfoProcessorService constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param EntityManagerInterface   $entityManager
     * @param LoggerInterface          $logger
     * @param OrderService             $orderService
     * @param ValidationHelper         $validator
     */
    public function __construct(
        AccountService $accountService,
        EventDispatcherInterface $dispatcher,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        OrderService $orderService,
        ValidationHelper $validator
    ) {
        $this->accountService = $accountService;
        $this->orderService = $orderService;
        parent::__construct($dispatcher, $entityManager, $logger, $validator);
    }

    /**
     * @return OrderService
     */
    public function getOrderService(): OrderService
    {
        return $this->orderService;
    }

    public function createOrderFromMessage(array $orderInfoMessage, $job)
    {
        try {
        } catch (\Exception $e) {
            $this->processingError($orderInfoMessage, $e, $job);
        }
    }

    /**
     * @param array $orderInfoMessage
     * @param Job   $job
     *
     * @return bool
     */
    public function process(array $orderInfoMessage, Job $job)
    {
        $order = null;
        try {
            $orderInfo = $this->accountService->createOrderInfoFromMessage($job->getAccount(), $orderInfoMessage);
            $order = $this->orderService->createOrderFromOrderInfo($orderInfo);

            $this->getValidator()->validate($orderInfo);
            $this->orderService->save($order);

            $this->getDispatcher()->dispatch(
                new OrderInfoProcessedEvent($orderInfo),
                OrderInfoProcessedEvent::getEventName());

            return true;
        } catch (\Exception $e) {
        }
    }

    /**
     * @param array                   $orderInfoMessage
     * @param \Exception              $exception
     * @param Job                     $job
     * @param OrderInfoInterface|null $orderInfo
     * @param Order|null              $order
     */
    private function processingError(
        array $orderInfoMessage,
        \Exception $exception,
        Job $job,
        ?OrderInfoInterface $orderInfo = null,
        ?Order $order = null
    ) {
        $this->getDispatcher()->dispatch(
            new OrderInfoProcessFailedEvent(
                $orderInfoMessage,
                $exception,
                $job,
                $order,
                $orderInfo
            ),
            OrderInfoProcessFailedEvent::getEventName()
        );
    }
}
