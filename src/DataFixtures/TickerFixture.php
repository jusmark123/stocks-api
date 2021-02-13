<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Constants\Brokerage\AlpacaConstants;
use App\Entity\Factory\TickerFactory;
use Doctrine\Persistence\ObjectManager;

class TickerFixture extends AbstractDataFixture
{
    const REFERENCE_ID = 'reference_id';
    const ACTIVE = 'active';
    const DESCRIPTION = 'description';
    const NAME = 'name';
    const TICKER = 'ticker';
    const CURRENCY = 'currency';
    const EXCHANGE = 'exchange';
    const TICKER_TYPE = 'type';
    const SECTOR = 'sector';
    const MARKET = 'market';
    const BROKERAGES = 'brokerages';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $item) {
            $ticker = TickerFactory::create()
                ->setTicker($item[self::TICKER])
                ->setActive($item[self::ACTIVE])
                ->setName($item[self::NAME])
                ->setMarket($item[self::MARKET])
                ->setCurrency($item[self::CURRENCY])
                ->setExchange($item[self::EXCHANGE])
                ->setSector($item[self::SECTOR])
                ->setTickerType($item[self::TICKER_TYPE])
                ->setBrokerages($item[self::BROKERAGES]);

            $manager->persist($ticker);

            $this->setReference('ticker_'.$item[self::TICKER], $ticker);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }

    protected function getData(): array
    {
        return [
            [
                self::TICKER => 'AAPL',
                self::ACTIVE => true,
                self::NAME => 'Apple Inc',
                self::MARKET => 'STOCKS',
                self::CURRENCY => 'USD',
                self::EXCHANGE => 'NASDAQ',
                self::TICKER_TYPE => $this->getReference('ticker_type_CS'),
                self::SECTOR => 'Technology',
                self::BROKERAGES => [
                    $this->getReference(AlpacaConstants::BROKERAGE_NAME),
                ],
            ],
            [
                self::TICKER => 'TSLA',
                self::ACTIVE => true,
                self::NAME => 'Tesla, Inc. Common Stock',
                self::MARKET => 'STOCKS',
                self::CURRENCY => 'USD',
                self::EXCHANGE => 'NASDAQ',
                self::TICKER_TYPE => $this->getReference('ticker_type_CS'),
                self::SECTOR => 'Consumer Cyclical',
                self::BROKERAGES => [
                    $this->getReference(AlpacaConstants::BROKERAGE_NAME),
                ],
            ],
            [
                self::TICKER => 'GME',
                self::ACTIVE => true,
                self::NAME => 'GameStop Corporation',
                self::MARKET => 'STOCKS',
                self::CURRENCY => 'USD',
                self::EXCHANGE => 'NYSE',
                self::TICKER_TYPE => $this->getReference('ticker_type_CS'),
                self::SECTOR => 'Consumer Cyclical',
                self::BROKERAGES => [
                    $this->getReference(AlpacaConstants::BROKERAGE_NAME),
                ],
            ],
            [
                self::TICKER => 'BBY',
                self::ACTIVE => true,
                self::NAME => 'Best Buy Co. Inc.',
                self::MARKET => 'STOCKS',
                self::CURRENCY => 'USD',
                self::EXCHANGE => 'NYSE',
                self::TICKER_TYPE => $this->getReference('ticker_type_CS'),
                self::SECTOR => 'Consumer Cyclical',
                self::BROKERAGES => [
                    $this->getReference(AlpacaConstants::BROKERAGE_NAME),
                ],
            ],
            [
                self::TICKER => 'NKLA',
                self::ACTIVE => true,
                self::NAME => 'Nikola Corporation Common Stock',
                self::MARKET => 'STOCKS',
                self::CURRENCY => 'USD',
                self::EXCHANGE => 'NASDAQ',
                self::TICKER_TYPE => $this->getReference('ticker_type_CS'),
                self::SECTOR => null,
                self::BROKERAGES => [
                    $this->getReference(AlpacaConstants::BROKERAGE_NAME),
                ],
            ],
        ];
    }
}
