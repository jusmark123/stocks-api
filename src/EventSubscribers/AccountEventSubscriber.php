<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventSubscribers;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Account;
use App\Service\AccountService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AccountEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * @var BrokerageService
     */
    private $brokerageService;

    /**
     * @var EntityManagerInterface
     */
    private $brokerageManager;

    /**
     * @param AccountService $accountService
     */
    public function __construct(AccountService $accountService)
    {
        $this->$accountService = $accountService;
    }

    /**
     * @return void
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                'getAccountInfo', EventPriorities::PRE_READ,
            ],
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function getAccountInfo(ViewEvent $event): void
    {
        $account = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$account instanceof Account || Request::METHOD_POST !== $method) {
            return;
        }

        $account->accountInfo = $brokerageService->getAccountInfo($account->brokerage);
    }
}
