<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service\Brokerage;

use App\Client\BrokerageClient;
use App\Constants\Brokerage\WebullConstants;
use App\Entity\Account;
use App\Helper\ValidationHelper;
use App\Service\JobService;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class WebullBrokerageService.
 */
class WebullBrokerageService extends AbstractBrokerageService
{
    protected const BROKERAGE_CONSTANTS = WebullConstants::class;

    /**
     * WebullBrokerageService constructor.
     *
     * @param Client                 $cache
     * @param BrokerageClient        $brokerageClient
     * @param EntityManagerInterface $entityManager
     * @param JobService             $jobService
     * @param LoggerInterface        $logger
     * @param ValidationHelper       $validator
     */
    public function __construct(
        BrokerageClient $brokerageClient,
        Client $cache,
        EntityManagerInterface $entityManager,
        JobService $jobService,
        LoggerInterface $logger,
        ValidationHelper $validator
    ) {
        parent::__construct($brokerageClient, $cache, $entityManager, $jobService, $logger, $validator);
        $this->brokerageClient = $brokerageClient;
    }

    /**
     * @param Account $account
     *
     * @throws ClientExceptionInterface
     *
     * @return mixed
     */
    private function login(Account $account): array
    {
        $data = [
            'account' => $account->getApiKey(),
            'accountType' => (string) WebullConstants::DEFAULT_ACCOUNT_TYPE,
            'deviceId' => $this->getDid($account),
            'deviceName' => WebullConstants::DEFAULT_DEVICE_NAME,
            'grade' => WebullConstants::DEFAULT_GRADE,
            'pwd' => md5(WebullConstants::PWD_SALT.$account->getApiSecret(), true),
            'regionId' => WebullConstants::DEFAULT_REGION,
//            'extInfo' => [
//                'codeAccountType' => WebullConstants::DEFAULT_ACCOUNT_TYPE,
//                'verificationCode' => '297593'
//            ],
        ];

        $headers = $this->getHeaders($account);
        $request = $this->brokerageClient->createRequest(
            WebullConstants::LOGIN_ENDPOINT,
            'POST',
            $headers,
            $data
        );
        $response = $this->brokerageClient->sendRequest($request);
        $result = json_decode($response->getBody(), true);

        if (\array_key_exists('accessToken', $result)) {
            $this->cache->setex(
                sprintf(WebullConstants::ACCESS_TOKEN_CACHE_KEY, $account->getGuid()->toString()),
                $result['tokenExpireTime'],
                $result['accountToken']
            );
        }

        return $result;
    }

    /**
     * @param Account $account
     * @param string  $path
     *
     * @return Uuid|string|null
     */
    private function getDid(Account $account, string $path = ''): string
    {
        $didCacheKey = sprintf('did:%s', $account->getGuid()->toString());
        $did = $this->cache->get($didCacheKey);

        if (null === $did) {
            $did = Uuid::uuid4();
            $this->cache->setex($didCacheKey, 86400, $did);
        }

        return $did;
    }

    /**
     * @param Account $account
     * @param false   $incTradeToken
     * @param false   $incTime
     * @param bool    $incZoneVar
     *
     * @return string[]
     */
    private function getHeaders(Account $account, $incTradeToken = false, $incTime = false, $incZoneVar = true): array
    {
        $accessToken = $this->cache->get(
            sprintf(WebullConstants::ACCESS_TOKEN_CACHE_KEY, $account->getGuid()->toString()));
        $tradeToken = $this->cache->get(
            sprintf(WebullConstants::TRADE_TOKEN_CACHE_KEY, $account->getGuid()->toString()));
        $headers = WebullConstants::HEADERS;
        $headers['did'] = $this->getDid($account);
        $headers['access_token'] = $accessToken;

        if ($incTradeToken) {
            $headers['t_token'] = $tradeToken;
        } elseif ($incTime) {
            $headers['t_time'] = (string) (time() * 1000);
        } elseif ($incZoneVar) {
            $header['lzone'] = 'PST';
        }

        return $headers;
    }
}
