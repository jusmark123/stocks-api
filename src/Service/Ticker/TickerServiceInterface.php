<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Ticker;

use App\DTO\Brokerage\TickerRequestInterface;

interface TickerServiceInterface
{
    /**
     * @param TickerRequestInterface $request
     *
     * @return array
     */
    public function fetchTickers(TickerRequestInterface $request): array;
}
