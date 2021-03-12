<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DTO\Brokerage\YahooFinance\Factory;

use App\DTO\Brokerage\YahooFinance\Entity\YahooFinanceTicker;
use App\Entity\Factory\TickerFactory;
use App\Entity\Ticker;
use App\Entity\TickerSector;
use App\Helper\SerializerHelper;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class YahooFinanceTickerFactory.
 */
class YahooFinanceTickerFactory
{
    /**
     * @return YahooFinanceTicker
     */
    public static function init(): YahooFinanceTicker
    {
        return new YahooFinanceTicker();
    }

    /**
     * @param array $data
     *
     * @throws ExceptionInterface
     *
     * @return YahooFinanceTicker
     */
    public static function create(array $data): YahooFinanceTicker
    {
        $serializer = SerializerHelper::ObjectNormalizer();

        return $serializer->denormalize($data, YahooFinanceTicker::class);
    }

    /**
     * @param array             $data
     * @param TickerSector|null $sector
     *
     * @throws ExceptionInterface
     *
     * @return Ticker
     */
    public static function createTicker(array $data, ?TickerSector $sector): Ticker
    {
        $ticker = self::create($data);

        return TickerFactory::create()
            ->setActive(true)
            ->setCurrency($ticker->getCurrency())
            ->setExchange($ticker->getExchange())
            ->setMarket($ticker->getQuoteType())
            ->setName($ticker->getShortName())
            ->setSector($sector)
            ->setTicker($ticker->getSymbol());
    }

    /**
     * @param array  $data
     * @param Ticker $ticker
     *
     * @throws ExceptionInterface
     *
     * @return Ticker
     */
    public static function updateTicker(array $data, Ticker $ticker): Ticker
    {
        $data = self::create($data);

        return $ticker->setActive(true)
            ->setCurrency($data->getCurrency())
            ->setExchange($data->getExchange())
            ->setMarket($data->getQuoteType())
            ->setName($data->getShortName())
            ->setTicker($data->getSymbol());
    }
}
