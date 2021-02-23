<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Ticker;

use App\DTO\Brokerage\YahooFinance\TickerRequest;
use App\Entity\Job;
use App\Entity\Ticker;
use App\Helper\ValidationHelper;
use App\Service\AbstractService;
use App\Service\Brokerage\PolygonBrokerageService;
use App\Service\DefaultTypeService;
use App\Service\Entity\TickerEntityService;
use Http\Message\RequestFactory;
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

    /**
     * @var Client
     */
    private Client $cache;

    /**
     * @var TickerServiceProvider
     */
    private TickerServiceProvider $tickerServiceProvider;

    /**
     * @var TickerEntityService
     */
    private TickerEntityService $tickerEntityService;

    /**
     * @var DefaultTypeService
     */
    private DefaultTypeService $defaultTypeService;

    /**
     * @var RequestFactory
     */
    private RequestFactory $requestFactory;

    /**
     * @var ValidationHelper
     */
    private ValidationHelper $validator;

    /**
     * TickerService constructor.
     *
     * @param TickerServiceProvider   $tickerServiceProvider
     * @param DefaultTypeService      $defaultTypeService
     * @param LoggerInterface         $logger
     * @param PolygonBrokerageService $polygonBrokerageService
     * @param TickerEntityService     $tickerEntityService
     * @param ValidationHelper        $validator
     */
    public function __construct(
        Client $cache,
        TickerServiceProvider $tickerServiceProvider,
        DefaultTypeService $defaultTypeService,
        LoggerInterface $logger,
        PolygonBrokerageService $polygonBrokerageService,
        TickerEntityService $tickerEntityService,
        ValidationHelper $validator
    ) {
        $this->cache = $cache;
        $this->tickerServiceProvider = $tickerServiceProvider;
        $this->defaultTypeService = $defaultTypeService;
        $this->tickerEntityService = $tickerEntityService;
        $this->polygonBrokerageService = $polygonBrokerageService;
        $this->validator = $validator;
        parent::__construct($logger);
    }

    /**
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function syncTickerTypes(): void
    {
        $this->polygonBrokerageService->syncTickerTypes();
    }

    /**
     * @param array $tickerData
     * @param Job   $job
     *
     * @return Ticker|null
     */
    public function syncTicker(array $tickerData, Job $job): ?Ticker
    {
        $account = $job->getAccount();
        $brokerageService = $this->brokerageServiceProvider->getBrokerageService($account->getBrokerage());
        $tickerInfo = $brokerageService->createTickerInfoFromMessage($tickerData);
        $ticker = $brokerageService->createTickerFromTickerInfo($tickerInfo, $job);
        $this->tickerEntityService->save($ticker);

        return $ticker;
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
        $elements = $table->childNodes->getElementsByTagName('tr');

        if (null !== $elements) {
            $resultarray = [];
            foreach ($elements as $key => $element) {
                if (0 === $key) {
                    continue;
                }
                $row = $element->childNodes;
                foreach ($row as $columns) {
                    $resultarray[] = $row->nodeValue;
                }
            }

            return $resultarray;
        }
    }
}
