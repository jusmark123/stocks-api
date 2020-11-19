<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Entity\Listener;

use App\Entity\Account;
use App\Entity\AccountService;
use App\Service\OrderService;

class AccountListener extends AbstractEntityListener
{
    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * @param Account $account
     */
    public function prePersist(Account $account): void
    {
    }
}
