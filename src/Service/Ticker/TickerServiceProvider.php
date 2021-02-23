<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Ticker;

class TickerServiceProvider
{
    /**
     * @var TickerServiceInterface[]
     */
    private iterable $tickerServices;

    /**
     * TickerServiceProvider constructor.
     *
     * @param array $tickerServices
     */
    public function __construct(iterable $tickerServices)
    {
        $this->tickerServices = $tickerServices;
    }

    /**
     * @param string $className
     *
     * @return TickerService
     */
    public function getTickerService(string $className): TickerServiceInterface
    {
        foreach ($this->tickerServices as $tickerService) {
            if ($className === \get_class($tickerService)) {
                return $tickerService;
            }
        }

        return $this->tickerServices[0];
    }
}
