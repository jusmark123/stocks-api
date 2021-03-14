<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Ticker;

use App\DTO\Brokerage\YahooFinance\TickerRequest;
use App\Helper\ValidationHelper;
use App\Service\AbstractService;
use App\Service\DefaultTypeService;
use App\Service\Entity\TickerEntityService;
use Predis\Client;
use Psr\Log\LoggerInterface;

/**
 * Class TickerService.
 */
class TickerService extends AbstractService
{
    const DEFAULT_TTL = 86400;
    const SP500_CACHE_KEY = 'sp500-%s';
    const NASDAQ_CACHE_KEY = 'nasdaq-%s';
    const DOW_CACHE_KEY = 'dow-%s';

    private Client $cache;
    private TickerServiceProvider $tickerServiceProvider;
    private TickerEntityService $tickerEntityService;
    private DefaultTypeService $defaultTypeService;
    private ValidationHelper $validator;
    private TickerEntityService $entityService;

    /**
     * TickerService constructor.
     *
     * @param Client                $cache
     * @param DefaultTypeService    $defaultTypeService
     * @param LoggerInterface       $logger
     * @param TickerEntityService   $entityService
     * @param TickerServiceProvider $tickerServiceProvider
     * @param ValidationHelper      $validator
     */
    public function __construct(
        Client $cache,
        DefaultTypeService $defaultTypeService,
        LoggerInterface $logger,
        TickerEntityService $entityService,
        TickerServiceProvider $tickerServiceProvider,
        ValidationHelper $validator
    ) {
        $this->cache = $cache;
        $this->tickerServiceProvider = $tickerServiceProvider;
        $this->defaultTypeService = $defaultTypeService;
        $this->entityService = $entityService;
        $this->validator = $validator;
        parent::__construct($logger);
    }

    /**
     * @param TickerRequest $request
     */
    public function fetchTickers(TickerRequest $request)
    {
        $tickerService = $this->tickerServiceProvider->getTickerService(YahooFinanceTickerService::class);
        $tickerService->fetchTickers($request);
    }

    /**
     * @param string $symbol
     *
     * @return mixed
     */
    public function getTickerBySymbol(string $symbol)
    {
        return $this->entityService->getEntityManager()->findOneBy(['ticker' => $symbol]);
    }

    /**
     * @return array
     */
    public function getSp500(): array
    {
        libxml_use_internal_errors(true);
        $tickers = $this->cache->get(sprintf(self::SP500_CACHE_KEY, date('Y-m-d')));
        if (null === $tickers) {
            $tickers = [];
            $dom = new \DomDocument();
            $dom->loadHTML(file_get_contents('https://en.wikipedia.org/wiki/List_of_S%26P_500_companies'));
            $table = $dom->getElementsByTagName('table')->item(0);
            $rows = $table->getElementsByTagName('tr');

            foreach ($rows as $key => $row) {
                if (0 === $key) {
                    continue;
                }
                $tickers[] = str_replace(["\n", "\r"], '', $row->getElementsByTagName('td')->item(0)->nodeValue);
            }
            sort($tickers);
            $this->cache->setex(
                sprintf(self::SP500_CACHE_KEY, date('Y-m-d')),
                self::DEFAULT_TTL,
                json_encode($tickers)
            );

            return $tickers;
        }

        return json_decode($tickers, true);
    }

    /**
     * @return array
     */
    public function getNasdaq(): array
    {
        $tickers = $this->cache->get(sprintf(self::NASDAQ_CACHE_KEY, date('Y-m-d')));

        if (null === $tickers) {
            $tickers = [];
            $data = file_get_contents('http://ftp.nasdaqtrader.com/dynamic/SymDir/nasdaqlisted.txt');
            $rows = explode("\r\n", $data);
            foreach ($rows as $key => $row) {
                if (0 === $key) {
                    continue;
                }
                $tickers[] = explode('|', $row)[0];
            }
            $tickers = array_unique(array_filter($tickers));
            sort($tickers);
            $this->cache->setex(
                sprintf(self::NASDAQ_CACHE_KEY, date('Y-m-d')),
                self::DEFAULT_TTL,
                json_encode($tickers)
            );

            return $tickers;
        }

        return json_decode($tickers, true);
    }

    /**
     * @return array
     */
    public function getDow(): array
    {
        libxml_use_internal_errors(true);
        $tickers = $this->cache->get(sprintf(self::DOW_CACHE_KEY, date('Y-m-d')));
        $tickers = null;

        if (null === $tickers) {
            $tickers = [];
            $ch = curl_init('https://www.slickcharts.com/dowjones');
            curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, \CURLOPT_HEADER, 1);
            curl_setopt($ch, \CURLOPT_HTTPHEADER, [
                'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_2_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.192 Safari/537.36',
                'Accept: text/html',
            ]);
            $dom = new \DomDocument();
            $data = curl_exec($ch);
            if (curl_error($ch)) {
                $error = curl_error($ch);
            }
            $dom->loadHTML($data);
            $table = $dom->getElementsByTagName('table')->item(0);
            $rows = $table->getElementsByTagName('tr');
            $idx = 0;
            foreach ($rows as $key => $row) {
                if (0 === $key) {
                    $headers = $row->getElementsByTagName('th');
                    foreach ($headers as $k => $header) {
                        if ('Symbol' === $header->nodeValue) {
                            $idx = $k;
                        }
                    }
                    continue;
                }
                $tickers[] = str_replace(["\n", "\r"], '', $row->getElementsByTagName('td')->item($idx)->nodeValue);
            }

            sort($tickers);
            $this->cache->setex(
                sprintf(self::DOW_CACHE_KEY, date('Y-m-d')),
                self::DEFAULT_TTL,
                json_encode($tickers)
            );

            return $tickers;
        }

        return json_decode($tickers, true);
    }

    private function parseTableToArray($table, $columns = 0)
    {
    }
}
