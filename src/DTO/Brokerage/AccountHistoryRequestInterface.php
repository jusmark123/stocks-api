<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage;

use App\Entity\Account;

/**
 * Interface AccountHistoryRequestInterface.
 */
interface AccountHistoryRequestInterface
{
    /**
     * @return Account
     */
    public function getAccount(): Account;

    /**
     * @return array
     */
    public function getParameters(): array;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasParameter(string $key): bool;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getParameter(string $key);

    /**
     * @param string $key
     * @param        $value
     *
     * @return mixed
     */
    public function setParameter(string $key, $value);
}
