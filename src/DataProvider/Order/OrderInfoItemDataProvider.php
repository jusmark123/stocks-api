<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider\Order;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;

class OrderInfoItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return false;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
    }
}
