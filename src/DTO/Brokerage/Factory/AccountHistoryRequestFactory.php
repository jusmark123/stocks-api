<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\Factory;

use App\DTO\Brokerage\AccountHistoryRequest;
use App\Entity\Factory\AbstractFactory;

class AccountHistoryRequestFactory extends AbstractFactory
{
    public static function init(): AccountHistoryRequest
    {
        return new AccountHistoryRequest();
    }

    public static function createFromFilters(array $filters): AccountHistoryRequest
    {
        $request = self::init();

        foreach ($filters as $filter => $value) {
            $method = sprintf('set%s', $filter);
            if (method_exists($request, $method)) {
                $request->$method($value);
            }
        }

        return $request;
    }
}
