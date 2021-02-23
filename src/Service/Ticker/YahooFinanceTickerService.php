<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Ticker;

use App\DTO\Brokerage\TickerRequestInterface;
use App\DTO\Brokerage\YahooFinance\Factory\YahooFinanceTickerFactory;
use App\DTO\Brokerage\YahooFinance\TickerRequest;
use App\Entity\Factory\TickerSectorFactory;
use App\Entity\Ticker;
use App\Entity\TickerSector;
use Doctrine\ORM\EntityManagerInterface;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;

/**
 * Class YahooFinanceTickerService.
 */
class YahooFinanceTickerService implements TickerServiceInterface
{
    /**
     * @var HttpClient
     */
    private HttpClient $client;

    /**
     * @var RequestFactory
     */
    private RequestFactory $requestFactory;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * YahooFinanceTickerService constructor.
     *
     * @param HttpClient             $client
     * @param RequestFactory         $requestFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        HttpClient $client,
        RequestFactory $requestFactory,
        EntityManagerInterface $entityManager
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @param TickerRequestInterface $request
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function fetchTickers(TickerRequestInterface $request): array
    {
        $sectors = [];
        $errors = [];
        foreach (TickerRequest::ASSET_TYPES as $type) {
            $count = 0;
            $request->setRange(range('a', 'z'));
            foreach ($request->getRange() as $letter) {
                $request->setQuery(sprintf('%s*', $letter))->setType($type);
                $uri = sprintf('%s?%s', $request::BASE_URL, http_build_query($request->getParams()));
                $req = $this->requestFactory->createRequest('GET', $uri, TickerRequest::REQUEST_HEADERS);
                $res = $this->client->sendRequest($req);
                $data = json_decode((string) $res->getBody(), true);

                try {
                    foreach ($data['finance']['result'][0]['documents'] as $ticker) {
                        $existingTicker = $this->entityManager
                            ->getRepository(Ticker::class)
                            ->findOneBy(['ticker' => $ticker['symbol']]);

                        $sector = null;
                        if (array_key_exists('industryName', $ticker)) {
                            $sector = $this->entityManager
                                ->getRepository(TickerSector::class)
                                ->findOneBy(['name' => $ticker['industryName']]);

                            if (null === $sector) {
                                $sector = TickerSectorFactory::create($ticker['industryName']);
                                $this->entityManager->persist($sector);
                                $this->entityManager->flush();
                            }
                        }

                        if ($existingTicker instanceof Ticker) {
                            $ticker = YahooFinanceTickerFactory::updateTicker($ticker, $existingTicker);
                        } else {
                            $ticker = YahooFinanceTickerFactory::createTicker($ticker, $sector);
                        }

                        $this->entityManager->persist($ticker);
                        ++$count;
                    }

                    $this->entityManager->flush();
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }

                if (null !== $request->getLimit() && $count >= $request->getLimit()) {
                    break;
                }
            }
        }

        return $errors;
    }
}
